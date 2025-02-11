<?php

namespace App\Http\Controllers\layanan;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Poli;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CekPendaftaranController extends Controller
{
    protected $form1;
    protected $form2;
    protected $form3;


    protected $title;
    protected $title_form1;
    protected $title_form2;
    protected $title_form3;


    public function __construct()
    {
        $this->title = 'Cek Pendaftaran';
        $this->title_form1 = 'Informasi Pasien';
        $this->title_form2 = 'Informasi Dokter & Poli';
        $this->title_form3 = 'Informasi Pendaftaran';


        $this->form1 = array(
            array(
                'label' => 'Nomor Rekam Medis',
                'field' => 'no_rm',
                'type' => 'text',
                'placeholder' => '',
                'width' => 6,
                'disabled' => true,

            ),
            array(
                'label' => 'NIK',
                'field' => 'nik',
                'width' => 6,
                'type' => 'number',
                'placeholder' => '',
                'disabled' => true,
            ),
            array(
                'label' => 'Nama Pasien',
                'field' => 'nama_pasien',
                'type' => 'text',
                'width' => 6,
                'placeholder' => '',
                'disabled' => true,


            ),
            array(
                'label' => 'Alamat',
                'field' => 'alamat',
                'type' => 'text',
                'width' => 6,
                'placeholder' => '',
                'disabled' => true,


            ),
            array(
                'label' => 'Email',
                'field' => 'email',
                'type' => 'email',
                'width' => 6,
                'placeholder' => '',
                'disabled' => true,

            ),
            array(
                'label' => 'NO. Hp (62xxx-xxxx-xxxx)',
                'field' => 'no_hp',
                'width' => 6,
                'type' => 'number',
                'placeholder' => '',
                'disabled' => true,

            ),
            array(
                'label' => 'Tanggal Lahir',
                'field' => 'tanggal_lahir',
                'type' => 'date',
                'placeholder' => '',
                'width' => 6,
                'disabled' => true,


            ),
            array(
                'label' => 'Jenis Kelamin (L/P)',
                'field' => 'jk',
                'type' => 'text',
                'width' => 6,
                'placeholder' => '',
                'disabled' => true,


            ),
        );

        $this->form2 = array(
            array(
                'label' => 'Poli',
                'field' => 'poli_id',
                'type' => 'text',
                'placeholder' => '',
                'width' => 6,
                'disabled' => true,

            ),
            array(
                'label' => 'Dokter',
                'field' => 'dokter_id',
                'type' => 'text',
                'placeholder' => '',
                'width' => 6,
                'disabled' => true,

            ),
            array(
                'label' => 'Spesialis',
                'field' => 'spesialis',
                'type' => 'text',
                'placeholder' => '',
                'width' => 6,
                'disabled' => true,

            ),
        );

        $this->form3 = array(
            array(
                'label' => 'No Pendaftaran',
                'field' => 'no_pendaftaran',
                'type' => 'text',
                'placeholder' => '',
                'width' => 12,
                'disabled' => true,

            ),
            array(
                'label' => 'Tanggal Daftar',
                'field' => 'tanggal_daftar',
                'type' => 'date',
                'placeholder' => '',
                'width' => 12,
                'disabled' => true,

            ),
            array(
                'label' => 'Keluhan',
                'field' => 'keluhan',
                'type' => 'textarea',
                'placeholder' => '',
                'width' => 12,
                'disabled' => true,

            ),
            array(
                'label' => 'Status',
                'field' => 'status',
                'type' => 'text',
                'placeholder' => '',
                'width' => 12,
                'disabled' => true,

            ),
        );
    }

    public function index()
    {
        return view('pages.layanans.pendaftaran.cekpendaftarans', 
            [
                'form1' => $this->form1, 
                'form2' => $this->form2,
                'form3' => $this->form3,

                'title' => $this->title,
                'title_form1' => $this->title_form1,
                'title_form2' => $this->title_form2,
                'title_form3' => $this->title_form3
            ]
        );
    }

    public function getInfoPendaftaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pendaftaran' => 'required|exists:data_pendaftaran,no_pendaftaran',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $no_pendaftaran = $request->id_pendaftaran;

        $pendaftaran = Pendaftaran::where('no_pendaftaran', $no_pendaftaran)
            ->leftjoin('mspasien', 'data_pendaftaran.pasien_id', '=', 'mspasien.no_rm')
            ->leftjoin('msdokter', 'data_pendaftaran.dokter_id', '=', 'msdokter.id')
            ->leftjoin('mspoli', 'data_pendaftaran.poli_id', '=', 'mspoli.id')
            ->select('*', 'data_pendaftaran.status as status_pendaftaran')
            ->first();
        
        // dd($pendaftaran);

        
        if (!$pendaftaran) {
            return response()->json(['message' => 'Pendaftaran not found'], 404);
        }

        return response()->json([
            'no_rm' => $pendaftaran->no_rm,
            'nik' => $pendaftaran->nik,
            'nama_pasien' => $pendaftaran->nama_pasien,
            'alamat' => $pendaftaran->alamat,
            'email' => $pendaftaran->email,
            'no_hp' => $pendaftaran->no_hp,
            'tanggal_lahir' => $pendaftaran->tanggal_lahir,
            'jk' => $pendaftaran->jk,
            'poli_id' => $pendaftaran->nama_poli,
            'dokter_id' => $pendaftaran->nama,
            'spesialis' => $pendaftaran->spesialisasi,
            'no_pendaftaran' => $pendaftaran->no_pendaftaran,
            'tanggal_daftar' => $pendaftaran->tanggal_daftar,
            'keluhan' => $pendaftaran->keluhan,
            'status' => $pendaftaran->status_pendaftaran
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'no_pendaftaran' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Pendaftaran::where('no_pendaftaran', $request->no_pendaftaran)->where('status', '!=', 'Terdaftar')->exists()) {
            return response()->json(['message' => 'Anda sudah melakukan konfirmasi pendaftaran'], 400);
        }

        $pendaftaran = Pendaftaran::where('no_pendaftaran', $request->no_pendaftaran)
        ->leftJoin('mspoli', 'data_pendaftaran.poli_id', 'mspoli.id')
        ->select('data_pendaftaran.*', 'mspoli.nama_poli')
        ->first();

        $tanggal_sekarang = Carbon::now()->format('Y-m-d');
        if ($pendaftaran->tanggal_daftar == $tanggal_sekarang) {

            $jumlahHariIni = Pendaftaran::whereDate('tanggal_daftar', $tanggal_sekarang)->where('poli_id', $pendaftaran->poli_id)->where('no_antrian', '!=', null)->count();
            // dd($jumlahHariIni);
            $noantrian = str_pad($jumlahHariIni + 1, 3, '0', STR_PAD_LEFT);
            // dd($noantrian, $jumlahHariIni);
            $noantri = $this->generateUniqueCode($pendaftaran->poli_id, $noantrian);
            $pendaftaran->update([
                'status' => 'Dalam Antrian',
                'no_antrian' => $noantri
            ]);
            // dd($pendaftaran);

            $this->storeRiwayat(Auth::user()->id, "pendaftaran", "UPDATEANTRIAN", json_encode($pendaftaran));


            return response()->json(['message' => 'Pendaftaran Berhasil', 'data' => $pendaftaran], 200);
        } else {
            return response()->json(['message' => 'Lakukan Konfirmasi Pendaftaran pada Tanggal Pendaftaran'], 400);
        }
    }

    public function generateUniqueCode($poli_id, $antrian) {
        $noantri = substr($antrian, -3);

        $poli = Poli::find($poli_id);
        
        $namaPoli = $poli->kd_poli;
        
        $uniqueCode = $namaPoli . $noantri;
        return $uniqueCode;
    }

    public function cetakReport($id) {
        if($id == null) {
            return response()->json(['message' => 'Pendaftaran not found'], 404);
        }





        

        

    }
}
