<?php

namespace App\Http\Controllers\layanan;

use App\Http\Controllers\Controller;
use App\Models\Diagnosa;
use App\Models\DiagnosisICD;
use App\Models\Dokter;
use App\Models\FacialTreatment;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\Skincare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PemeriksaanPasienController extends Controller
{
    protected $form_daftar;

    public function __construct() {
        $this->form_daftar = array(
            array(
                'label' => 'Kode Pendaftaran',
                'field' => 'pemeriksaan_kdPendaftaran',
                'type' => 'hidden',
                'placeholder' => '',
                'width' => 0,
                'hidden' => true
            ),
            array(
                'label' => 'ID Pasien',
                'field' => 'pemeriksaan_idPasien',
                'type' => 'hidden',
                'placeholder' => '',
                'width' => 0,
                'hidden' => true
            ),
            array(
                'label' => 'Keluhan',
                'field' => 'pemeriksaan_keluhan',
                'type' => 'textarea',
                'placeholder' => 'Masukkan keluhan',
                'width' => 6,
                'required' => true
            ),
            array(
                'label' => 'Kondisi Kulit',
                'field' => 'pemeriksaan_kondisiKulit',
                'type' => 'textarea',
                'placeholder' => 'Masukkan kondisi kulit',
                'width' => 6,
                'required' => true

            ),
            array(
                'label' => 'Diagnosa',
                'field' => 'pemeriksaan_diagnosis',
                'type' => 'textarea',
                'placeholder' => 'Masukkan diagnosa',
                'width' => 6,
                'required' => true

            ),
            array(
                'label' => 'Rekomendasi Treatment',
                'field' => 'pemeriksaan_rekTreatment',
                'type' => 'textarea',
                'placeholder' => 'Masukkan rekomendasi treatment',
                'width' => 6,

            ),
            array(
                'label' => 'Rekomendasi Skincare',
                'field' => 'pemeriksaan_rekSkincare',
                'type' => 'textarea',
                'placeholder' => 'Masukkan rekomendasi skincare',
                'width' => 6,
            ),
            array(
                'label' => 'Catatan',
                'field' => 'pemeriksaan_note',
                'type' => 'textarea',
                'placeholder' => 'Masukkan catatan',
                'width' => 6,
            ),
        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pendaftaran::where('tanggal_daftar', date('Y-m-d'))
                            ->whereNotIn('status', ['Terdaftar', 'Gagal'])
                            ->get();
            return datatables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-outline-primary btn-sm editProduct"><i class="fa-regular fa-pen-to-square"></i> Periksa</a>';
                        
                            return $btn;
                    })
                    ->editColumn('dokter_id', function($row) {
                        return Dokter::find($row->dokter_id)->nama;
                    })
                    ->addColumn('nama_pasien', function($row) {
                        return Pasien::where('no_rm', $row->pasien_id)->first()->nama_pasien;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('pages.layanans.pendaftaran.pemeriksaanpasien', ['title' => "Data Pemeriksaan Pasien"]);
    }

    public function getDataDiagnosis($pasien_id, Request $request)
    {
        if ($request->ajax()) {
            $pasienId = Pasien::where('no_rm', $pasien_id)->value('id');
            $data = Pemeriksaan::where('pemeriksaan_idPasien', $pasienId)
                            ->get();
            return datatables()::of($data)
                    ->editColumn('pemeriksaan_rekTreatment', function($row) {
                        return $row->pemeriksaan_rekTreatment ?? '-';
                    })
                    ->editColumn('pemeriksaan_kdPendaftaran', function($row) {
                        return Pendaftaran::find($row->pemeriksaan_kdPendaftaran)->value('no_pendaftaran') ?? '-';
                    })
                    ->editColumn('created_at', function($row) {
                        return $row->created_at->format('Y-m-d H:i:s');
                    })
                    ->editColumn('pemeriksaan_rekSkincare', function($row) {
                        $rekSkincare = json_decode($row->pemeriksaan_rekSkincare, true);
                        return is_array($rekSkincare) ? implode(', ', $rekSkincare) : ($row->pemeriksaan_rekSkincare ?? '-');
                    })
                    ->editColumn('pemeriksaan_note', function($row) {
                        return $row->pemeriksaan_note ?? '-';
                    })
                    ->make(true);
        }
        
    }

    public function show($id)
    {
        $pendaftaran = Pendaftaran::where('no_pendaftaran', $id)
            ->leftjoin('mspasien', 'data_pendaftaran.pasien_id', '=', 'mspasien.no_rm')
            ->leftjoin('msdokter', 'data_pendaftaran.dokter_id', '=', 'msdokter.id')
            ->select('data_pendaftaran.*', 'mspasien.*', 'msdokter.nama', 'msdokter.spesialisasi')
            ->first();
        return view('pages.layanans.pendaftaran.actionpemeriksaanpasien', ['pendaftaran' => $pendaftaran, 'title' => "Data Pemeriksaan Pasien", 'form_daftar' => $this->form_daftar]);
    }

    public function generateUniqueCode($id) {
        $date = date('ym');
        $pelangganPart = substr($id, -3);


        $uniqueCode = "DP" . $date . $pelangganPart;

        return $uniqueCode;
    }

    public function generateUniqueCodePembayaran($id) {
        $date = date('ym');
        $pelangganPart = substr($id, -2);


        $uniqueCode = "TRX" . $date . $pelangganPart;

        return $uniqueCode;
    }


public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'kd_pendaftaran' => 'required',
        'pasien_id' => 'required',
        'pemeriksaan_kondisiKulit' => 'required|string',
        'pemeriksaan_diagnosis' => 'required|string',
        'pemeriksaan_rekTreatment' => 'nullable',
        'pemeriksaan_rekSkincare' => 'nullable|array',
        'pemeriksaan_note' => 'nullable|string',
    ]);


    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $idPendaftaran = Pendaftaran::where('no_pendaftaran', $request->kd_pendaftaran)->first();


    $diagnosa = Pemeriksaan::updateOrCreate(
        ['pemeriksaan_kdPendaftaran' => $idPendaftaran->id],
        [
            'pemeriksaan_idPasien' => Pasien::where('no_rm', $request->pasien_id)->value('id'),
            'pemeriksaan_keluhan' => $idPendaftaran->keluhan ?? 'unknown',
            'pemeriksaan_kondisiKulit' => $request->pemeriksaan_kondisiKulit,
            'pemeriksaan_diagnosis' => $request->pemeriksaan_diagnosis,
            'pemeriksaan_rekTreatment' => $request->pemeriksaan_rekTreatment,
            'pemeriksaan_rekSkincare' => json_encode($request->pemeriksaan_rekSkincare),
            'pemeriksaan_jumlahSkincare' => json_encode($request->pemeriksaan_jumlahSkincare),
            'pemeriksaan_note' => $request->pemeriksaan_note,
        ],
    );


    // hitung total
    $total = 0;
    if ($diagnosa->pemeriksaan_rekTreatment) {
        $total += FacialTreatment::where('facialTreatment_id', $diagnosa->pemeriksaan_rekTreatment)->value('facialTreatment_harga');
    }
    if ($diagnosa->pemeriksaan_rekSkincare) {
        $resepObat = json_decode($diagnosa->pemeriksaan_rekSkincare);
        $jumlahObat = json_decode($diagnosa->pemeriksaan_jumlahSkincare);
        foreach ($resepObat as $key => $skincareItem) {
            $total += Skincare::where('skincare_id', $skincareItem)->value('skincare_harga') * $jumlahObat[$key];
        }
    }


    $pembayaranCount = Pembayaran::whereYear('created_at', date('Y'))
    ->whereMonth('created_at', date('m'))
    ->count();
    $no_pem = str_pad($pembayaranCount + 1, 4, '0', STR_PAD_LEFT);

    $pembayaranKD = Pembayaran::where('no_pemeriksaan', $diagnosa->id)->first()->no_pembayaran ?? null;

    $pembayaran = Pembayaran::updateOrCreate(['no_pemeriksaan' => $diagnosa->id],
    [
        'no_pembayaran' => $pembayaranKD === null ? $this->generateUniqueCodePembayaran($no_pem) : $pembayaranKD,
        'no_pemeriksaan' => $diagnosa->id,
        'pasien_id' => $diagnosa->pemeriksaan_idPasien,
        'dokter' => $idPendaftaran->dokter_id,
        'tanggal_pemeriksaan' => $diagnosa->created_at,
        'treatment' => $diagnosa->pemeriksaan_rekTreatment,
        'skincare' => $diagnosa->pemeriksaan_rekSkincare,
        'jumlahSkincare' => $diagnosa->pemeriksaan_jumlahSkincare,
        'total' => $total,
        'status' => 'Belum Bayar',
    ]);

    $resepObat = json_decode($diagnosa->pemeriksaan_rekSkincare);
    $jumlahObat = json_decode($diagnosa->pemeriksaan_jumlahSkincare);
    foreach ($resepObat as $key => $skincareItem) {
        $skincare = Skincare::where('skincare_id', $skincareItem)->first();
        $skincare->skincare_stok = $skincare->skincare_stok - $jumlahObat[$key];
        $skincare->save();
    }

    $idPendaftaran->update([
        'status' => "Selesai",
    ]);

    $this->storeRiwayat(Auth::user()->id, "pemeriksaan", "INSERT", json_encode($diagnosa));
    $this->storeRiwayat(Auth::user()->id, "pembayarans", "INSERT", json_encode($pembayaran));



    return response()->json(['success' => 'Data pemeriksaan berhasil disimpan.']);
}
}
