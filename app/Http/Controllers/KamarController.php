<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KamarController extends Controller
{
    protected $form;
    protected $title;

    public function __construct()
    {
        $this->title = 'Kamar';

        $this->form = array(
            array(
                'label' => 'Tipe Kamar',
                'field' => 'tipe_kamar',
                'type' => 'text',
                'width' => 6,
                'placeholder' => 'Masukan Tipe Kamar',
                'required' => true
            ),
            array(
                'label' => 'Fasilitas',
                'field' => 'fasilitas',
                'type' => 'text',
                'width' => 6,
                'placeholder' => 'Masukan Fasilitas',
                'required' => true
            ),
            array(
                'label' => 'Tarif Kamar',
                'field' => 'tarif_kamar',
                'type' => 'number',
                'width' => 6,
                'placeholder' => 'Masukan Tarif Kamar',
                'required' => true
            ),
            array(
                'label' => 'Jumlah Kamar',
                'field' => 'jumlah_kamar',
                'type' => 'number',
                'width' => 6,
                'placeholder' => 'Masukan Jumlah Kamar',
                'required' => true
            ),
            array(
                'label' => 'Status',
                'field' => 'status',
                'type' => 'select',
                'width' => 6,
                'placeholder' => 'Masukan Status',
                'required' => true,
                'options' => [
                    'tersedia' => 'Tersedia',
                    'tidak' => 'Tidak Tersedia'
                ]

            ),
        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kamar::all();
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

        return view('pages.kamars', ['form' => $this->form, 'title' => $this->title]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipe_kamar' => 'required|string|max:255',
            'fasilitas' => 'required|string|max:255',
            'tarif_kamar' => 'required|numeric',
            'jumlah_kamar' => 'required|integer',
            'status' => 'required|in:tersedia,tidak',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $kamar = Kamar::updateOrCreate(
            ['id' => $request->id],
            [
                'tipe_kamar' => $request->tipe_kamar,
                'fasilitas' => $request->fasilitas,
                'tarif_kamar' => $request->tarif_kamar,
                'jumlah_kamar' => $request->jumlah_kamar,
                'status' => $request->status
            ]
        );

        $this->storeRiwayat(Auth::user()->id, "mskamar", "INSERT", json_encode($kamar));

        return response()->json(['success' => 'Kamar berhasil disimpan.']);
    }

    
    public function edit($id)
    {
        $kamar = Kamar::find($id);
        return response()->json($kamar);
    }

    // Fungsi untuk mengupdate data yang telah diedit
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tipe_kamar' => 'required|string|max:255',
            'fasilitas' => 'required|string|max:255',
            'tarif_kamar' => 'required|numeric',
            'jumlah_kamar' => 'required|integer',
            'status' => 'required|in:tersedia,tidak',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $kamar = Kamar::find($id);
        $kamar->update([
            'tipe_kamar' => $request->tipe_kamar,
            'fasilitas' => $request->fasilitas,
            'tarif_kamar' => $request->tarif_kamar,
            'jumlah_kamar' => $request->jumlah_kamar,
            'status' => $request->status,
        ]);

        $this->storeRiwayat(Auth::user()->id, "mskamar", "UPDATE", json_encode($kamar));

        return response()->json(['success' => 'Kamar updated successfully.']);
    }

    // Fungsi untuk menghapus data
    public function destroy($id)
    {   
        $this->storeRiwayat(Auth::user()->id, "mskamar", "DELETE", json_encode(Kamar::find($id)));
        Kamar::find($id)->delete();
        return response()->json(['success' => 'Kamar deleted successfully.']);
    }

    public function getKamar()
    {
        $Kamar = Kamar::all();
        return response()->json($Kamar);
    }
}
