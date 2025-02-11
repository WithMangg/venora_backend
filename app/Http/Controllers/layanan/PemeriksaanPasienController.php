<?php

namespace App\Http\Controllers\layanan;

use App\Http\Controllers\Controller;
use App\Models\Diagnosa;
use App\Models\DiagnosisICD;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PemeriksaanPasienController extends Controller
{
    protected $form_daftar;

    public function __construct() {
        $this->form_daftar = array(
            array(
                'label' => 'Pasien ID',
                'field' => 'pasien_id',
                'type' => 'hidden',
                'placeholder' => '',
                'width' => 0,
                'hidden' => true
            ),
            array(
                'label' => 'No. Pendaftaran',
                'field' => 'no_pendaftaran',
                'placeholder' => '',
                'type' => 'hidden',
                'width' => 0,
                'hidden' => true
            ),
            
            array(
                'label' => 'Hasil Pemeriksaan',
                'field' => 'hasil_pemeriksaan',
                'type' => 'textarea',
                'placeholder' => 'Masukkan hasil pemeriksaan',
                'width' => 6,
                'required' => true
            ),
            array(
                'label' => 'Diagnosa',
                'field' => 'diagnosa',
                'type' => 'textarea',
                'placeholder' => 'Masukkan diagnosa',
                'width' => 6,
                'required' => true

            ),
            array(
                'label' => 'Tindakan',
                'field' => 'tindakan',
                'type' => 'textarea',
                'placeholder' => 'Masukkan tindakan',
                'width' => 6,
                'required' => true

            ),
            array(
                'label' => 'Resep Obat',
                'field' => 'resep_obat',
                'type' => 'textarea',
                'placeholder' => 'Masukkan resep obat',
                'width' => 6,

            ),
            array(
                'label' => 'Pemeriksaan Lanjutan',
                'field' => 'pemeriksaan_lanjutan',
                'type' => 'textarea',
                'placeholder' => 'Masukkan pemeriksaan lanjutan',
                'width' => 6,
            ),
            array(
                'label' => 'Catatan',
                'field' => 'catatan',
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
                    ->editColumn('poli_id', function($row) {
                        return Poli::find($row->poli_id)->nama_poli;
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
        
            $data = Pemeriksaan::where('pasien_id', $pasien_id)
                            ->get();
            return datatables()::of($data)
                    ->editColumn('diagnosis_utama', function($row) {
                        return DiagnosisICD::where('code', $row->diagnosis_utama)->first()->name_id;
                    })
                    ->make(true);
        }
        
    }

    public function show($id)
    {
        $pendaftaran = Pendaftaran::where('no_pendaftaran', $id)
            ->leftjoin('mspasien', 'data_pendaftaran.pasien_id', '=', 'mspasien.no_rm')
            ->leftjoin('msdokter', 'data_pendaftaran.dokter_id', '=', 'msdokter.id')
            ->leftjoin('mspoli', 'data_pendaftaran.poli_id', '=', 'mspoli.id')
            ->select('data_pendaftaran.*', 'mspasien.*', 'mspoli.*', 'msdokter.nama', 'msdokter.spesialisasi')
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
        'pasien_id' => 'required',
            'keluhan_utama' => 'required|string',
            'riwayat_penyakit_sekarang' => 'required|string',
            'tinggi_badan' => 'nullable|numeric',
            'berat_badan' => 'nullable|numeric',
            'tekanan_darah' => 'nullable|string',
            'suhu_tubuh' => 'nullable|numeric',
            'nadi' => 'nullable|numeric',
            'frekuensi_napas' => 'nullable|numeric',
            'diagnosis_utama' => 'required|exists:diagnosis_icd,code', 
            'diagnosis_pendukung' => 'nullable|exists:diagnosis_icd,code',
            'tindakan_medis' => 'nullable|array',
            'resep_obat' => 'nullable|array',
            'jumlah_obat' => 'nullable|array',
            'konsultasi_lanjutan' => 'nullable|string',
            'rujukan' => 'required',
            'anjuran_dokter' => 'nullable|string',
            'status_pulang' => 'required|string|in:berobat_jalan,sehat,rujuk,meninggal',
    ]);


    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $diagnosaCount = Pemeriksaan::whereYear('created_at', date('Y'))
    ->whereMonth('created_at', date('m'))
    ->count();

    $diagnosaRM = str_pad($diagnosaCount + 1, 4, '0', STR_PAD_LEFT);
    $diagnosaKD = Pemeriksaan::where('kd_pendaftaran', $request->kd_pendaftaran)->first()->kd_diagnosa ?? null;

    $diagnosa = Pemeriksaan::updateOrCreate(
        ['kd_pendaftaran' => $request->kd_pendaftaran],
        [
        'pasien_id' => $request->pasien_id,
        'keluhan_utama' => $request->keluhan_utama,
        'riwayat_penyakit_sekarang' => $request->riwayat_penyakit_sekarang,
        'tinggi_badan' => $request->tinggi_badan,
        'berat_badan' => $request->berat_badan,
        'tekanan_darah' => $request->tekanan_darah,
        'suhu_tubuh' => $request->suhu_tubuh,
        'nadi' => $request->nadi,
        'frekuensi_napas' => $request->frekuensi_napas,
        'diagnosis_utama' => $request->diagnosis_utama,
        'diagnosis_pendukung' => $request->diagnosis_pendukung,
        'tindakan_medis' => json_encode($request->tindakan_medis),
        'resep_obat' => json_encode($request->resep_obat),
        'jumlah_obat' => json_encode($request->jumlah_obat),
        'rujukan' => $request->rujukan,
        'anjuran_dokter' => $request->anjuran_dokter,
        'status_pulang' => $request->status_pulang,
        'kd_diagnosa' => $diagnosaKD === null ? $this->generateUniqueCode($diagnosaRM) : $diagnosaKD,
            'tanggal_diagnosa' => now(),
            'kd_pendaftaran' => $request->kd_pendaftaran,
        ],
    );

    $pendaftaran = Pendaftaran::where('no_pendaftaran', $request->kd_pendaftaran)->first();

    // hitung total
    $total = 0;
    if ($diagnosa->tindakan_medis) {
        $tindakanMedis = json_decode($diagnosa->tindakan_medis);
        foreach ($tindakanMedis as $tindakanMedisItem) {
            $total += Diagnosa::where('kd_diagnosa', $tindakanMedisItem)->value('harga');
        }
    }
    if ($diagnosa->resep_obat) {
        $resepObat = json_decode($diagnosa->resep_obat);
        $jumlahObat = json_decode($diagnosa->jumlah_obat);
        foreach ($resepObat as $key => $resepObatItem) {
            $total += Obat::where('medicine_id', $resepObatItem)->value('harga') * $jumlahObat[$key];
        }
    }


    $pembayaranCount = Pemeriksaan::whereYear('created_at', date('Y'))
    ->whereMonth('created_at', date('m'))
    ->count();
    $no_pembayaran = str_pad($pembayaranCount + 1, 4, '0', STR_PAD_LEFT);

    $pembayaranKD = Pembayaran::where('no_diagnosa', $diagnosa->kd_diagnosa)->first()->no_pembayaran ?? null;
    $pembayaran = Pembayaran::updateOrCreate(['no_diagnosa' => $diagnosa->kd_diagnosa],
    [
        'no_pembayaran' => $pembayaranKD === null ? $this->generateUniqueCodePembayaran($no_pembayaran) : $pembayaranKD,
        'no_diagnosa' => $diagnosa->kd_diagnosa,
        'no_rm' => $diagnosa->pasien_id,
        'nama_pasien' => Pasien::where('no_rm', $diagnosa->pasien_id)->first()->nama_pasien,
        'poli' => Poli::find($pendaftaran->poli_id)->nama_poli,
        'dokter' => Dokter::find($pendaftaran->dokter_id)->nama,
        'tanggal_pemeriksaan' => $diagnosa->tanggal_diagnosa,
        'tindakan_medis' => $diagnosa->tindakan_medis,
        'resep_obat' => $diagnosa->resep_obat,
        'jumlah_obat' => $diagnosa->jumlah_obat,
        'total' => $total,
        'status' => 'Belum Bayar',
    ]);

    $pendaftaran->update([
        'status' => "Selesai",
    ]);

    $this->storeRiwayat(Auth::user()->id, "pemeriksaan", "INSERT", json_encode($diagnosa));
    $this->storeRiwayat(Auth::user()->id, "pembayarans", "INSERT", json_encode($pembayaran));



    return response()->json(['success' => 'Data pemeriksaan berhasil disimpan.']);
}
}
