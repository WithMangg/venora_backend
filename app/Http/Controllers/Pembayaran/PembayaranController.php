<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\Diagnosa;
use App\Models\Dokter;
use App\Models\FacialTreatment;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Skincare;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    protected $title;

    public function __construct()
    {
        $this->title = 'Pembayaran';
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->data == 'sudah') {
                $data = Pembayaran::where('status', 'Sudah Bayar')->get();
            } else {
                $data = Pembayaran::where('status', '!=', 'Sudah Bayar')->get();
            }
            return datatables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit viewData"><i class="ti ti-eye"></i></a>';

                            return $btn;
                    })
                    ->addColumn('noPembayaran', function($row) {
                        if (!$row->no_pemeriksaan) {
                            return $row->no_pembayaran . '<br> <span style="color: #6c757d">No. Pendaftaran: ' . "Tidak Tersedia" . '</span>';

                        }
                        $pemeriksaan = Pemeriksaan::find($row->no_pemeriksaan)->value('pemeriksaan_kdPendaftaran') ?? '-';
                        $pendaftaran = Pendaftaran::find($pemeriksaan)->value('no_pendaftaran') ?? '-';
                        return $row->no_pembayaran . '<br> <span style="color: #6c757d">No. Pendaftaran: ' . $pendaftaran . '</span>';
                    })
                    ->editColumn('nama_pasien', function($row) {
                        $pasien = Pasien::find($row->pasien_id)->first();
                        return $pasien->nama_pasien . '<br> <span style="color: #6c757d">No.RM: ' . $pasien->no_rm . '</span>';
                    })
                    ->editColumn('total', function($row) {
                        return 'Rp. ' . number_format($row->total, 0, ',', '.');
                    })
                    ->editColumn('status', function($row) {
                        return '<span class="badge ' . ($row->status === 'Sudah Bayar' ? 'border-success text-success' : 'border-danger text-danger') . '">' . $row->status . '</span>';
                    })

                    ->rawColumns(['action', 'status', 'nama_pasien', 'dokter', 'noPembayaran'])
                    
                    ->make(true);
        }

        return view('pages.pembayaran.pembayaran', ['title' => $this->title]);
    }

    public function edit($id) {
        $pembayaran = Pembayaran::find($id);

        $dokter = Dokter::find($pembayaran->dokter);
        $pembayaran->dokter = $dokter ? $dokter->nama : '-';

        if (!$pembayaran) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $pasien = Pasien::find($pembayaran->pasien_id);
        $treatment = FacialTreatment::where('facialTreatment_id', $pembayaran->treatment)->get();
        
        $kodeSkincare = json_decode($pembayaran->skincare);
        $jumlahSkincare = json_decode($pembayaran->jumlahSkincare);
        $skincare = Skincare::whereIn('skincare_id', $kodeSkincare)->get();
        
        return response()->json(
            [
                'status' => true,
                'message' => 'Success',
                'info' => $pembayaran,
                'pasien' => $pasien,
                'treatment' => $treatment,
                'skincares' => $skincare,
                'jumlah_obat' => $jumlahSkincare

            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'bayar' => 'required|numeric',
            'kembali' => 'required'
        ]);

        $pembayaran = Pembayaran::where('no_pembayaran',$request->user_id);
        $kembalian = (int) $request->kembali;

        $pembayaran->update([
            'bayar' => $request->bayar,
            'kembali' => $kembalian,
            'status' => 'Sudah Bayar'
        ]);

        return response()->json([
            'message' => 'Layanan diperbarui!',
            'status' => true,
        ]);
    }

    
}
