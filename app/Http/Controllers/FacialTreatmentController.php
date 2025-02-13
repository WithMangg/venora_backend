<?php

namespace App\Http\Controllers;

use App\Models\FacialTreatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FacialTreatmentController extends Controller
{
    protected $form;
    protected $title;

    public function __construct()
    {
        $this->title = 'Facial Treatment';

        $this->form = array(
            array(
                'label' => 'ID Facial Treatment',
                'field' => 'facialTreatment_id',
                'type' => 'text',
                'placeholder' => '',
                'width' => 6,
                'disabled' => true
            ),
            array(
                'label' => 'Foto',
                'field' => 'facialTreatment_foto',
                'width' => 6,
                'type' => 'file',
                'placeholder' => 'Upload Foto',
                'required' => false
            ),
            array(
                'label' => 'Nama Treatment',
                'field' => 'facialTreatment_nama',
                'width' => 6,
                'type' => 'text',
                'placeholder' => 'Masukkan Nama Treatment',
                'required' => true
            ),
            array(
                'label' => 'Harga',
                'field' => 'facialTreatment_harga',
                'type' => 'number',
                'width' => 6,
                'placeholder' => 'Masukkan Harga',
                'required' => true
            ),
            array(
                'label' => 'Durasi (menit)',
                'field' => 'facialTreatment_durasi',
                'type' => 'number',
                'width' => 6,
                'placeholder' => 'Masukkan Durasi',
                'required' => true
            ),
            array(
                'label' => 'Benefit',
                'field' => 'facialTreatment_benefit',
                'type' => 'textarea',
                'width' => 6,
                'placeholder' => 'Masukkan Benefit',
                'required' => true
            ),
            array(
                'label' => 'Deskripsi',
                'field' => 'facialTreatment_deskripsi',
                'type' => 'textarea',
                'width' => 6,
                'placeholder' => 'Masukkan Deskripsi',
                'required' => true
            ),
        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FacialTreatment::all();
            return datatables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-outline-primary btn-sm editProduct"><i class="fa-regular fa-pen-to-square"></i> Edit</a>';
                        $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-danger btn-sm deleteProduct"><i class="fa-solid fa-trash"></i> Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }



        return view('pages.facial', ['form' => $this->form, 'title' => $this->title]);
    }

    public function generateUniqueCode($id)
    {
        $date = date('ym');
        $treatmentPart = substr($id, -3);
        $uniqueCode = "FACIAL" . $date . $treatmentPart . rand(100, 999);
        return $uniqueCode;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facialTreatment_nama' => 'required',
            'facialTreatment_harga' => 'required|numeric',
            'facialTreatment_durasi' => 'required|integer',
            'facialTreatment_deskripsi' => 'required',
            'facialTreatment_benefit' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $treatmentCount = FacialTreatment::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();
        $treatmentKD = str_pad($treatmentCount + 1, 2, '0', STR_PAD_LEFT);

        $fotoBase64 = null;
        if ($request->hasFile('facialTreatment_foto')) {
            $fotoBase64 = base64_encode(file_get_contents($request->file('facialTreatment_foto')->getRealPath()));
        }

        $userimage = FacialTreatment::where('id', $request->user_id)->first();

        $treatment = FacialTreatment::updateOrCreate(
            ['id' => $request->input('user_id')],
            [
                'facialTreatment_id' => $request->input('facialTreatment_id') ? $request->input('facialTreatment_id') : $this->generateUniqueCode($treatmentKD),
                'facialTreatment_nama' => $request->input('facialTreatment_nama'),
                'facialTreatment_harga' => $request->input('facialTreatment_harga'),
                'facialTreatment_durasi' => $request->input('facialTreatment_durasi'),
                'facialTreatment_deskripsi' => $request->input('facialTreatment_deskripsi'),
                'facialTreatment_benefit' => $request->input('facialTreatment_benefit'),
                'facialTreatment_foto' => $fotoBase64 ? $fotoBase64 : $userimage->facialTreatment_foto,
            ]
        );

        $this->storeRiwayat(Auth::user()->id, "msFacialTreatment", "INSERT/UPDATE", json_encode($treatment));

        return response()->json(['success' => 'Medicine berhasil disimpan.']);
    }

    public function edit($id)
    {
        $medicine = FacialTreatment::find($id);
        return response()->json($medicine);
    }

    // Fungsi untuk menghapus data
    public function destroy($id)
    {
        $FacialTreatment = FacialTreatment::find($id);
        $this->storeRiwayat(Auth::user()->id, "msSkincare", "DELETE", json_encode($FacialTreatment));
        
        FacialTreatment::find($id)->delete();
        return response()->json(['success' => 'Medicine deleted successfully.']);
    }

    public function getSkincare()
    {
        $Skincare = FacialTreatment::all();
        return response()->json($Skincare);
    }

}
