<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ObatController extends Controller
{
    protected $form;
    protected $title;

    public function __construct()
    {
        $this->title = 'Obat';

        $this->form = array(
            array(
                'label' => 'ID Obat',
                'field' => 'medicine_id',
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
                'label' => 'Nama Obat',
                'field' => 'nama_obat',
                'width' => 6,
                'type' => 'text',
                'placeholder' => 'Masukkan Nama Obat',
                'required' => true
            ),
            array(
                'label' => 'Nama Generik',
                'field' => 'nama_generik',
                'type' => 'text',
                'width' => 6,
                'placeholder' => 'Masukkan Nama Generik',
                'required' => true

            ),
            array(
                'label' => 'Kategori',
                'field' => 'kategori',
                'type' => 'text',
                'width' => 6,
                'placeholder' => 'Masukkan Kategori',
                'required' => true

            ),
            array(
                'label' => 'Bentuk Dosis',
                'field' => 'bentuk_dosis',
                'type' => 'text',
                'width' => 6,
                'placeholder' => 'Masukkan Bentuk Dosis',
                'required' => true

            ),
            array(
                'label' => 'Kekuatan',
                'field' => 'kekuatan',
                'type' => 'text',
                'width' => 6,
                'placeholder' => 'Masukkan Kekuatan',
                'required' => true
            ),
            array(
                'label' => 'Harga',
                'field' => 'harga',
                'type' => 'number',
                'width' => 6,
                'placeholder' => 'Masukkan Harga',
                'required' => true
            ),
            array(
                'label' => 'Jumlah Stok',
                'field' => 'jumlah_stok',
                'type' => 'number',
                'width' => 6,
                'placeholder' => 'Masukkan Jumlah Stok',
                'required' => true
            ),
            array(
                'label' => 'Tanggal Kedaluwarsa',
                'field' => 'tanggal_kedaluwarsa',
                'type' => 'date',
                'placeholder' => '',
                'width' => 6,
                'required' => true

            ),
            array(
                'label' => 'Produsen',
                'field' => 'produsen',
                'type' => 'text',
                'width' => 6,
                'placeholder' => 'Masukkan Produsen',
                'required' => true

            ),
            array(
                'label' => 'Instruksi Penggunaan',
                'field' => 'instruksi_penggunaan',
                'type' => 'textarea',
                'width' => 6,
                'placeholder' => 'Masukkan Instruksi Penggunaan',
                'required' => true

            ),
            array(
                'label' => 'Efek Samping',
                'field' => 'efek_samping',
                'type' => 'textarea',
                'width' => 6,
                'placeholder' => 'Masukkan Efek Samping',
                'required' => true

            ),
            array(
                'label' => 'Instruksi Penyimpanan',
                'field' => 'instruksi_penyimpanan',
                'type' => 'textarea',
                'width' => 6,
                'placeholder' => 'Masukkan Instruksi Penyimpanan',
                'required' => true
            ),
        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Obat::all();
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

        return view('pages.obats', ['form' => $this->form, 'title' => $this->title]);
    }

    public function generateUniqueCode($id) {
        $date = date('ym');
        $medicinePart = substr($id, -3);
        $uniqueCode = "OB" . $date . $medicinePart . rand(100, 999);
        return $uniqueCode;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'nama_obat' => 'required',
            'nama_generik' => 'required',
            'bentuk_dosis' => 'required',
            'harga' => 'required|numeric',
            'jumlah_stok' => 'required|integer',
            'tanggal_kedaluwarsa' => 'required|date',
            'produsen' => 'required',
            'instruksi_penggunaan' => 'required',
            'efek_samping' => 'required',
            'instruksi_penyimpanan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $obatCount = Obat::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();
        $obatKD = str_pad($obatCount + 1, 2, '0', STR_PAD_LEFT);
        
        // Inisialisasi foto
        $fotoBase64 = null;
        if ($request->hasFile('foto')) {
            $fotoBase64 = base64_encode(file_get_contents($request->file('foto')->getRealPath()));
        }

        $medicine = Obat::updateOrCreate(
            ['id' => $request->input('user_id')],
            [
                'medicine_id' => $request->input('medicine_id') ? $request->input('medicine_id') : $this->generateUniqueCode($obatKD),
                'nama_obat' => $request->input('nama_obat'),
                'nama_generik' => $request->input('nama_generik'),
                'kategori' => $request->input('kategori'),
                'bentuk_dosis' => $request->input('bentuk_dosis'),
                'kekuatan' => $request->input('kekuatan'),
                'harga' => $request->input('harga'),
                'jumlah_stok' => $request->input('jumlah_stok'),
                'tanggal_kedaluwarsa' => $request->input('tanggal_kedaluwarsa'),
                'produsen' => $request->input('produsen'),
                'instruksi_penggunaan' => $request->input('instruksi_penggunaan'),
                'efek_samping' => $request->input('efek_samping'),
                'instruksi_penyimpanan' => $request->input('instruksi_penyimpanan'),
                'foto' => $fotoBase64 ? $fotoBase64 : $request->input('foto')
            ]
        );


        $this->storeRiwayat(Auth::user()->id, "msobat", "INSERT/UPDATE", json_encode($medicine));

        return response()->json(['success' => 'Medicine berhasil disimpan.']);
    }

    public function edit($id)
    {
        $medicine = Obat::find($id);
        return response()->json($medicine);
    }

    // Fungsi untuk menghapus data
    public function destroy($id)
    {
        $obat = Obat::find($id);
        $this->storeRiwayat(Auth::user()->id, "msobat", "DELETE", json_encode($obat));
        
        Obat::find($id)->delete();
        return response()->json(['success' => 'Medicine deleted successfully.']);
    }

    public function getObat()
    {
        $Obat = Obat::all();
        return response()->json($Obat);
    }


}
