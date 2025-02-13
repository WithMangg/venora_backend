<?php

namespace App\Http\Controllers;

use App\Models\Skincare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SkincareController extends Controller
{
    protected $form;
    protected $title;

    public function __construct()
    {
        $this->title = 'Skincare';

        $this->form = array(
            array(
                'label' => 'ID Skincare',
                'field' => 'skincare_id',
                'type' => 'text',
                'placeholder' => '',
                'width' => 6,
                'disabled' => true
            ),
            array(
                'label' => 'Foto',
                'field' => 'foto',
                'width' => 6,
                'type' => 'file',
                'placeholder' => 'Masukkan Foto',
                'required' => false
            ),
            array(
                'label' => 'Nama Skincare',
                'field' => 'skincare_nama',
                'width' => 6,
                'type' => 'text',
                'placeholder' => 'Masukkan Nama Skincare',
                'required' => true
            ),
            array(
                'label' => 'Brand',
                'field' => 'skincare_brand',
                'type' => 'text',
                'width' => 6,
                'placeholder' => 'Masukkan Nama Brand',
                'required' => true

            ),
            array(
                'label' => 'Kategori',
                'field' => 'skincare_kategori',
                'type' => 'select',
                'width' => 6,
                'placeholder' => 'Masukkan Kategori',
                'required' => true,
                'options' => [
                    'Moisturizer' => 'Moisturizer',
                    'Sunblock' => 'Sunblock',
                    'Toner' => 'Toner',
                    'Essence' => 'Essence',
                    'Serum' => 'Serum',
                    'Eye Cream' => 'Eye Cream',
                    'Face Mist' => 'Face Mist',
                    'Cleanser' => 'Cleanser'
                ]

            ),
            array(
                'label' => 'Harga',
                'field' => 'skincare_harga',
                'type' => 'number',
                'width' => 6,
                'placeholder' => 'Masukkan Harga',
                'required' => true
            ),
            array(
                'label' => 'Jumlah Stok',
                'field' => 'skincare_stok',
                'type' => 'number',
                'width' => 6,
                'placeholder' => 'Masukkan Jumlah Stok',
                'required' => true
            ),
            array(
                'label' => 'Penggunaan',
                'field' => 'skincare_penggunaan',
                'type' => 'textarea',
                'width' => 6,
                'placeholder' => 'Masukkan Instruksi Penggunaan',
                'required' => true

            ),
            array(
                'label' => 'Deksripsi',
                'field' => 'skincare_deskripsi',
                'type' => 'textarea',
                'width' => 6,
                'placeholder' => 'Masukkan Efek Samping',
                'required' => true

            ),
        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Skincare::all();
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

        return view('pages.skincare', ['form' => $this->form, 'title' => $this->title]);
    }

    public function generateUniqueCode($id) {
        $date = date('ym');
        $medicinePart = substr($id, -3);
        $uniqueCode = "SKIN" . $date . $medicinePart . rand(100, 999);
        return $uniqueCode;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'skincare_nama' => 'required',
            'skincare_brand' => 'required',
            'skincare_kategori' => 'required',
            'skincare_harga' => 'required|numeric',
            'skincare_stok' => 'required|integer',
            'skincare_penggunaan' => 'required',
            'skincare_deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $SkincareCount = Skincare::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();
        $SkincareKD = str_pad($SkincareCount + 1, 2, '0', STR_PAD_LEFT);
        
        // Inisialisasi foto
        $fotoBase64 = null;
        if ($request->hasFile('foto')) {
            $fotoBase64 = base64_encode(file_get_contents($request->file('foto')->getRealPath()));
        }

        $userimage = Skincare::where('id', $request->user_id)->first();


        $medicine = Skincare::updateOrCreate(
            ['id' => $request->input('user_id')],
            [
                'skincare_id' => $request->input('skincare_id') ? $request->input('skincare_id') : $this->generateUniqueCode($SkincareKD),
                'skincare_nama' => $request->input('skincare_nama'),
                'skincare_brand' => $request->input('skincare_brand'),
                'skincare_kategori' => $request->input('skincare_kategori'),
                'skincare_harga' => $request->input('skincare_harga'),
                'skincare_stok' => $request->input('skincare_stok'),
                'skincare_penggunaan' => $request->input('skincare_penggunaan'),
                'skincare_deskripsi' => $request->input('skincare_deskripsi'),
                'foto' => $fotoBase64 ? $fotoBase64 : $userimage->foto
            ]
        );


        $this->storeRiwayat(Auth::user()->id, "msSkincare", "INSERT/UPDATE", json_encode($medicine));

        return response()->json(['success' => 'Medicine berhasil disimpan.']);
    }

    public function edit($id)
    {
        $medicine = Skincare::find($id);
        return response()->json($medicine);
    }

    // Fungsi untuk menghapus data
    public function destroy($id)
    {
        $Skincare = Skincare::find($id);
        $this->storeRiwayat(Auth::user()->id, "msSkincare", "DELETE", json_encode($Skincare));
        
        Skincare::find($id)->delete();
        return response()->json(['success' => 'Medicine deleted successfully.']);
    }

    public function getSkincare()
    {
        $Skincare = Skincare::all();
        return response()->json($Skincare);
    }


}
