<?php

namespace App\Http\Controllers;

use App\Models\DataPoli;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PoliController extends Controller
{
    protected $form;
    protected $title;

    public function __construct()
    {
        

        $this->title = 'Poli';

        $this->form = array(
            array(
                'label' => 'Kode Poli',
                'field' => 'kd_poli',
                'type' => 'text',
                'placeholder' => '',
                'width' => 12,
                'required' => true
            ),
            array(
                'label' => 'Nama Poli',
                'field' => 'nama_poli',
                'type' => 'text',
                'placeholder' => '',
                'width' => 12,
                'required' => true
            ),
            array(
                'label' => 'Deskripsi',
                'field' => 'deskripsi',
                'width' => 12,
                'type' => 'text',
                'placeholder' => '',
                'required' => false
            ),
        );
    }

    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Poli::all();
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

        return view('pages.polis', ['form' => $this->form, 'title' => $this->title]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kd_poli' => 'required',
            'nama_poli' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $poli = Poli::updateOrCreate(
            ['id' => $request->id],
            [
                'kd_poli' => $request->kd_poli,
                'nama_poli' => $request->nama_poli,
                'deskripsi' => $request->deskripsi,
            ]
        );
        
        $this->storeRiwayat(Auth::user()->id, "mspoli", "INSERT", json_encode($poli));


        return response()->json(['success' => 'Poli berhasil disimpan.']);
    }

    public function edit($id)
    {
        $poli = Poli::find($id);
        return response()->json($poli);
    }

    // Fungsi untuk mengupdate data yang telah diedit
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kd_poli' => 'required',
            'nama_poli' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $poli = Poli::find($id);
        $poli->update(
            [
                'kd_poli' => $request->kd_poli,
                'nama_poli' => $request->nama_poli,
                'deskripsi' => $request->deskripsi,
            ]
        );

        $this->storeRiwayat(Auth::user()->id, "mspoli", "UPDATE", json_encode($poli));


        return response()->json(['success' => 'Poli updated successfully.']);
    }

    // Fungsi untuk menghapus data
    public function destroy($id)
    {
        $poli = Poli::find($id);

        $this->storeRiwayat(Auth::user()->id, "mspoli", "DELETE", json_encode($poli));
        
        $datapoli = DataPoli::where('poli_id', $id)->get();
        foreach ($datapoli as $dp) {
            $dp->delete();
        }
        $poli->delete();
        
        return response()->json(['success' => 'Poli deleted successfully.']);
    }
}
