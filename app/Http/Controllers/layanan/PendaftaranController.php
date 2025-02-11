<?php

namespace App\Http\Controllers\layanan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PasienController;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PendaftaranController extends Controller
{
    protected $form;
    protected $form_daftar;

    protected $title;

    public function __construct()
    {
        $this->title = 'Pendaftaran Pasien';

        $this->form = array(
            array(
                'label' => 'Nomor Rekam Medis',
                'field' => 'no_rm',
                'type' => 'text',
                'placeholder' => '',
                'width' => 3,
            ),
            array(
                'label' => 'NIK',
                'field' => 'nik',
                'width' => 3,
                'type' => 'number',
                'placeholder' => '',
                'required' => true,
                'disabled' => true,
            ),
            array(
                'label' => 'Nama Pasien',
                'field' => 'nama_pasien',
                'type' => 'text',
                'width' => 3,
                'placeholder' => '',
                'required' => true,
                'disabled' => true,


            ),
            array(
                'label' => 'Alamat',
                'field' => 'alamat',
                'type' => 'text',
                'width' => 3,
                'placeholder' => '',
                'required' => true,
                'disabled' => true,


            ),
            array(
                'label' => 'Email',
                'field' => 'email',
                'type' => 'email',
                'width' => 3,
                'placeholder' => '',
                'disabled' => true,

            ),
            array(
                'label' => 'NO. Hp (62xxx-xxxx-xxxx)',
                'field' => 'no_hp',
                'width' => 3,
                'type' => 'number',
                'placeholder' => '',
                'disabled' => true,

            ),
            array(
                'label' => 'Tanggal Lahir',
                'field' => 'tanggal_lahir',
                'type' => 'date',
                'placeholder' => '',
                'width' => 3,
                'required' => true,
                'disabled' => true,


            ),
            array(
                'label' => 'Jenis Kelamin (L/P)',
                'field' => 'jk',
                'type' => 'text',
                'width' => 3,
                'placeholder' => '',
                'required' => true,
                'disabled' => true,


            ),
        );

        $this->form_daftar = array(
            array(
                'label' => 'Poli',
                'field' => 'poli_id',
                'type' => 'select',
                'placeholder' => 'Pilih Poli',
                'width' => 4,
                'options' => Poli::pluck('nama_poli', 'id')->toArray(),
                'required' => true
            ),
            array(
                'label' => 'Dokter',
                'field' => 'dokter_id',
                'type' => 'select',
                'placeholder' => 'Pilih Dokter',
                'width' => 4,
                'options' => Dokter::pluck('nama', 'id')->toArray(),
                'required' => true
            ),
            array(
                'label' => 'Tanggal Daftar',
                'field' => 'tanggal_daftar',
                'type' => 'date',
                'placeholder' => '',
                'width' => 4,
                'required' => true,
            ),
            array(
                'label' => 'Keluhan',
                'field' => 'keluhan',
                'type' => 'textarea',
                'placeholder' => 'Masukkan keluhan',
                'width' => 12,
                'required' => true,
            ),
        );
    }

    public function index(Request $request)
    {
        return view('pages.layanans.pendaftaran.pendaftarans', ['form' => $this->form, 'form_daftar' => $this->form_daftar ,'title' => $this->title]);
    }

    public function show($id)
    {
        $user = Pasien::where('no_rm', $id)->first(); 

        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }


    public function generateUniqueCode($id, $poli_id, $tanggal) {
        $dokterPart = substr($id, -3);

        $poli = Poli::find($poli_id);
        
        $namaPoli = $poli->kd_poli;
        $date = date('Ymd', strtotime($tanggal));
        

        $uniqueCode = $namaPoli . $date . $dokterPart;

        return $uniqueCode;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'poli_id' => 'required|exists:mspoli,id',
            'dokter_id' => 'required|exists:msdokter,id',
            'tanggal_daftar' => 'required|date',
            'keluhan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Pasien::where('no_rm', $request->no_rm)->doesntExist()) {
            return response()->json(['error' => 'Data pasien tidak ditemukan'], 422);
        }

        if (Pendaftaran::where('pasien_id', $request->no_rm)
            ->where('poli_id', $request->poli_id)
            ->whereDate('tanggal_daftar', $request->tanggal_daftar)
            ->exists()) {
            return response()->json(['error' => 'Pasien sudah terdaftar di poli ini untuk tanggal yang dipilih'], 421);
        }

        $pendaftaranCount = Pendaftaran::whereDate('tanggal_daftar', $request->tanggal_daftar)
                                ->where('poli_id', $request->poli_id)
                                ->count();
        $dokterKD = str_pad($pendaftaranCount + 1, 4, '0', STR_PAD_LEFT);

        $pendaftaran = Pendaftaran::create([
            'no_pendaftaran' => $this->generateUniqueCode($dokterKD, $request->poli_id, $request->tanggal_daftar),
            'pasien_id' => $request->no_rm,
            'poli_id' => $request->poli_id,
            'dokter_id' => $request->dokter_id,
            'tanggal_daftar' => $request->tanggal_daftar,
            'keluhan' => $request->keluhan,
            'status' => 'Terdaftar'
        ]);

        $data_pendaftar = [
            'no_pendaftaran' => $pendaftaran->no_pendaftaran,
            'nama_pasien' => Pasien::where('no_rm', $pendaftaran->pasien_id)->first()->nama_pasien,
            'nama_dokter' => Dokter::where('id', $pendaftaran->dokter_id)->first()->nama,
        ];



        $this->storeRiwayat(Auth::user()->id, "pendaftaran", "INSERT", json_encode($pendaftaran));

        return response()->json(['success' => 'Pendaftaran berhasil disimpan.', 'data' => $data_pendaftar], 200);
    }

    public function getPendaftarans(Request $request)
    {
        if ($request->ajax()) {
            $data = Pendaftaran::orderBy('id', 'asc')->get();
            return datatables()::of($data)
                    ->addIndexColumn()

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

        return view('pages.layanans.pendaftaran.listpendaftarans', ['title' => "Data Pendaftaran Pasien"]);
    }
}
