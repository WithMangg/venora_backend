<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    protected $form;
    protected $title;

    public function __construct()
    {
        $this->title = 'Pasien';

        $this->form = array(
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
                'placeholder' => 'Masukkan NIK',
                'required' => true
            ),
            array(
                'label' => 'Nama Pasien',
                'field' => 'nama_pasien',
                'type' => 'text',
                'width' => 6,
                'placeholder' => 'Masukkan Nama',
                'required' => true

            ),
            array(
                'label' => 'Alamat',
                'field' => 'alamat',
                'type' => 'textarea',
                'width' => 6,
                'placeholder' => 'Masukkan Alamat',
                'required' => true

            ),
            array(
                'label' => 'Email',
                'field' => 'email',
                'type' => 'email',
                'width' => 6,
                'placeholder' => 'Masukkan Email',
            ),
            array(
                'label' => 'NO. Hp (62xxx-xxxx-xxxx)',
                'field' => 'no_hp',
                'width' => 6,
                'type' => 'number',
                'placeholder' => 'Masukkan NO. Hp',
            ),
            array(
                'label' => 'Tanggal Lahir',
                'field' => 'tanggal_lahir',
                'type' => 'date',
                'placeholder' => '',
                'width' => 6,
                'required' => true

            ),
            array(
                'label' => 'Jenis Kelamin (L/P)',
                'field' => 'jk',
                'type' => 'text',
                'width' => 6,
                'placeholder' => '',
                'required' => true

            ),
            array(
                'label' => 'Pekerjaan',
                'field' => 'pekerjaan',
                'type' => 'text',
                'width' => 6,
                'placeholder' => '',
            ),
            array(
                'label' => 'Kewarganegaraan',
                'field' => 'kewarganegaraan',
                'type' => 'text',
                'width' => 6,
                'placeholder' => '',
            ),
            array(
                'label' => 'Agama',
                'field' => 'agama',
                'width' => 6,
                'type' => 'text',
                'placeholder' => '',
            ),
            array(
                'label' => 'Pendidikan',
                'field' => 'pendidikan',
                'width' => 6,
                'type' => 'text',
                'placeholder' => '',
            ),
            array(
                'label' => 'Status',
                'field' => 'status',
                'type' => 'select',
                'width' => 6,
                'placeholder' => 'Masukan Status',
                'required' => true,
                'options' => [
                    'aktif' => 'Aktif',
                    'tidak' => 'Tidak Aktif'
                ]

            ),
        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pasien::all();
            return datatables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
     
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-outline-primary btn-sm editProduct"><i class="fa-regular fa-pen-to-square"></i> Edit</a>';
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-danger btn-sm deleteProduct"><i class="fa-solid fa-trash"></i> Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('pages.pasiens', ['form' => $this->form, 'title' => $this->title]);
    }

    public function generateUniqueCode() {
        // mengambil data pelanggan terakhir
        $pasienCount = Pasien::whereYear('created_at', date('Y'))
                                ->whereMonth('created_at', date('m'))
                                ->count();

        // menghitung jumlah pelanggan terakhir
        $pasienRM = str_pad($pasienCount + 1, 3, '0', STR_PAD_LEFT);

        $date = date('ym');
        $pelangganPart = substr($pasienRM, -3);
        
        // membuat unique code
        $uniqueCode = "RM" . $date . $pelangganPart;

        return $uniqueCode;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|max:33',
            'nama_pasien' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'jk' => 'required|max:1',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Pasien::updateOrCreate(
            ['id' => $request->id],
            ['nama_pasien' => $request->nama_pasien, 'email' => $request->email, 'nik' => $request->nik, 'no_hp' => $request->no_hp, 'tanggal_lahir' => $request->tanggal_lahir,
             'alamat' => $request->alamat, 'jk' => $request->jk, 'pekerjaan' => $request->pekerjaan,'kewarganegaraan' => $request->kewarganegaraan, 
             'agama' => $request->agama, 'pendidikan' => $request->pendidikan, 'status' => $request->status, 'no_rm' => $this->generateUniqueCode()]
        );

        $userpengguna = User::updateOrCreate(
            ['email' => $user->email],
            ['name' => $request->nama_pasien, 
             'email' => $user->email, 
             'password' => Hash::make('password'),
             'role_id' => '2',
            ]
        );

        $this->storeRiwayat(Auth::user()->id, "mspasien", "INSERT", json_encode($user));

        return response()->json(['success' => 'User berhasil disimpan.']);
    }

    public function edit($id)
    {
        $user = Pasien::find($id);
        return response()->json($user);
    }

    // Fungsi untuk mengupdate data yang telah diedit
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|max:33',
            'nama_pasien' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'jk' => 'required|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Pasien::find($id);
        $user->update(
            [
                'nama_pasien' => $request->nama_pasien, 'email' => $request->email, 
                'nik' => $request->nik, 'no_hp' => $request->no_hp, 
                'tanggal_lahir' => $request->tanggal_lahir,'alamat' => $request->alamat, 
                'jk' => $request->jk, 'pekerjaan' => $request->pekerjaan, 
                'kewarganegaraan' => $request->kewarganegaraan, 'agama' => $request->agama, 
                'pendidikan' => $request->pendidikan, 'status' => $request->status, 
            ]
        );

        $this->storeRiwayat(Auth::user()->id, "mspasien", "UPDATE", json_encode($user));

        return response()->json(['success' => 'User updated successfully.']);
    }

    // Fungsi untuk menghapus data
    public function destroy($id)
    {
        $user = Pasien::find($id);
        $this->storeRiwayat(Auth::user()->id, "mspasien", "DELETE", json_encode($user));
        
        Pasien::find($id)->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }
}
