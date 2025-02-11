<?php

namespace App\Http\Controllers;

use App\Imports\DiagnosaImport;
use App\Models\Diagnosa;
use App\Models\DiagnosisICD;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class DiagnosaController extends Controller
{
    protected $form;
    protected $title;

    public function __construct()
    {
        $this->title = 'Layanan Pemeriksaan';

        $this->form = array(
            array(
                'label' => 'Kategori Layanan',
                'field' => 'kategori',
                'type' => 'select',
                'options' => Diagnosa::groupBy('kategori')->pluck('kategori'),
                'placeholder' => 'Pilih Kategori',
                'width' => 12,
            ),
            array(
                'label' => 'Nama Layanan',
                'field' => 'diagnosa',
                'type' => 'text',
                'placeholder' => '',
                'width' => 12,
                'required' => true
            ),

            array(
                'label' => 'Harga Layanan',
                'field' => 'harga',
                'type' => 'number',
                'placeholder' => '',
                'width' => 12,
                'required' => true
            )
        );
    }

    public function getOptions(Request $request)
    {
        $search = $request->query('q', '');
        $page = $request->query('page', 1); 
        $limit = 50; 
        $offset = ($page - 1) * $limit;

        $query = DiagnosisICD::select('id', 'code' ,'name_id') 
            ->when($search, function ($q) use ($search) {
                return $q->where('name_id', 'like', "%$search%");
            });

        $total = $query->count();
        $options = $query->offset($offset)->limit($limit)->get();

        $results = $options->map(function ($item) {
            return [
                'id' => $item->code,
                'text' => $item->name_id // Pastikan 'text' ada
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $offset + $limit < $total, 
            ],
        ]);
    }

    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Diagnosa::all();
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

        return view('pages.diagnosas', ['form' => $this->form, 'title' => $this->title]);
    }


    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'diagnosa' => 'required',
            'harga' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate unique code
        $latestDiagnosa = Diagnosa::orderBy('id', 'desc')->first();
        $newCode = '001';

        if ($latestDiagnosa) {
            $lastCode = intval(substr($latestDiagnosa->id, -3));
            $newCode = str_pad($lastCode + 1, 3, '0', STR_PAD_LEFT);
        }


        $diagnosa = Diagnosa::updateOrCreate(
            ['id' => $request->id],
            [
                'kd_diagnosa' => $newCode,
                'kategori' => $request->kategori,
                'diagnosa' => $request->diagnosa,
                'harga' => $request->harga,
            ]
        );

        $this->storeRiwayat(Auth::user()->id, "msdiagnosa", "INSERT", json_encode($diagnosa));
        
        return response()->json(['success' => 'Diagnosa berhasil disimpan.']);
    }

    public function edit($id)
    {
        $diagnosa = Diagnosa::find($id);
        return response()->json($diagnosa);
    }

public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'diagnosa' => 'required',
        'harga' => 'required|numeric',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $diagnosa = Diagnosa::find($id);
    $diagnosa->update([
        'kategori' => $request->kategori,
        'diagnosa' => $request->diagnosa,
        'harga' => $request->harga,
    ]);

    $this->storeRiwayat(Auth::user()->id, "msdiagnosa", "UPDATE", json_encode($diagnosa));

    return response()->json(['success' => 'Diagnosa berhasil diperbarui.']);
}

    public function destroy($id)
    {
        $diagnosa = Diagnosa::find($id);
        $this->storeRiwayat(Auth::user()->id, "msdiagnosa", "DELETE", json_encode($diagnosa));
        $diagnosa->delete();
        return response()->json(['success' => 'Diagnosa berhasil dihapus.']);
    }

    public function import(Request $request)
    {
        // Validasi file upload
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Import file menggunakan Maatwebsite Excel
        Excel::import(new DiagnosaImport, $request->file('file'));

        return response()->json(['success' => 'Data diagnosa berhasil diimport.']);
    }
}
