<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataPoli;
use App\Models\Dokter;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DataPoliController extends Controller
{
    protected $form;
    protected $title;

    public function __construct()
    {
        $this->title = 'Data Poli';

        $this->form = array(
            array(
                'label' => 'Poli',
                'field' => 'poli_id',
                'type' => 'select',
                'width' => 6,
                'options' => Poli::pluck('nama_poli', 'id')->toArray(),
                'placeholder' => 'Pilih Poli',
                'required' => true
            ),
            array(
                'label' => 'Dokter',
                'field' => 'dokter_id',
                'type' => 'select',
                'width' => 6,
                'options' => Dokter::whereNotIn('id', DataPoli::pluck('dokter_id')->toArray())->pluck('nama', 'id')->toArray(),
                'placeholder' => 'Pilih Dokter',
                'required' => true
            ),
        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DataPoli::all();
            return datatables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
     
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-danger btn-sm deleteProduct"><i class="fa-solid fa-trash"></i> Delete</a>';
    
                            return $btn;
                    })
                    ->editColumn('poli_id', function($row) {
                        return Poli::find($row->poli_id)->nama_poli;
                    })
                    ->editColumn('dokter_id', function($row) {
                        return Dokter::find($row->dokter_id)->nama;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('pages.data.datapolis', ['form' => $this->form, 'title' => $this->title]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'poli_id' => 'required|numeric',
            'dokter_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $poli = DataPoli::updateOrCreate(
            ['id' => $request->id],
            [
                'poli_id' => $request->poli_id,
                'dokter_id' => $request->dokter_id,
            ]
        );

        $this->storeRiwayat(Auth::user()->id, "data_poli", "INSERT", json_encode($poli));


        return response()->json(['success' => 'Data Poli berhasil disimpan.']);
    }

    
    public function edit($id)
    {
        $poli = DataPoli::find($id);
        return response()->json($poli);
    }

    // Fungsi untuk mengupdate data yang telah diedit
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'poli_id' => 'required|numeric',
            'dokter_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $poli = DataPoli::find($id);
        $poli->update([
            'poli_id' => $request->poli_id,
            'dokter_id' => $request->dokter_id,
        ]);

        $this->storeRiwayat(Auth::user()->id, "data_poli", "UPDATE", json_encode($poli));


        return response()->json(['success' => 'Data Poli updated successfully.']);
    }

    // Fungsi untuk menghapus data
    public function destroy($id)
    {
        $poli = DataPoli::find($id);
        $this->storeRiwayat(Auth::user()->id, "data_poli", "DELETE", json_encode($poli));

        $poli->delete();
        return response()->json(['success' => 'Data Poli deleted successfully.']);
    }

    public function getDokterByPoliId($poliId)
    {
        $dokter = DataPoli::where('poli_id', $poliId)->leftjoin('msdokter', 'data_poli.dokter_id', '=', 'msdokter.id')->select('msdokter.*')->get();
        // dd($dokter);
        return response()->json($dokter);
    }
}
