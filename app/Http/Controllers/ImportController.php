<?php

namespace App\Http\Controllers;

use App\Imports\KamarImport;
use App\Imports\ObatImport;
use App\Imports\PasienImport;
use App\Imports\PoliImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    protected $title;

    public function __construct()
    {
        $this->title = 'Import Data';
    }

    public function index()
    {
        return view('pages.importdata', ['title' => $this->title]);
    }

    public function importPasien(Request $request)
    {
        // Validasi file upload
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv,ods|max:2048', // Validasi file excel/csv
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Proses import file
        try {
            Excel::import(new PasienImport, $request->file('file'));
            return response()->json(['success' => 'Data pasien berhasil diimpor.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat impor: ' . $e->getMessage()], 500);
        }
    }

    public function importPoli(Request $request)
    {
        // Validasi file upload
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv,ods|max:2048', // Validasi file excel/csv
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Proses import file
        try {
            Excel::import(new PoliImport, $request->file('file'));
            return response()->json(['success' => 'Data Poli berhasil diimpor.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat impor: ' . $e->getMessage()], 500);
        }
    }

    public function importKamar(Request $request)
    {
        // Validasi file upload
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv,ods|max:2048', // Validasi file excel/csv
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Proses import file
        try {
            Excel::import(new KamarImport, $request->file('file'));
            return response()->json(['success' => 'Data Poli berhasil diimpor.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat impor: ' . $e->getMessage()], 500);
        }
    }

    public function importObat(Request $request)
    {
        // Validasi file upload
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv,ods|max:2048', // Validasi file excel/csv
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Proses import file
        try {
            Excel::import(new ObatImport, $request->file('file'));
            return response()->json(['success' => 'Data Poli berhasil diimpor.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat impor: ' . $e->getMessage()], 500);
        }
    }
}
