<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class DataRiwayatPasienController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pasienId = null;
            if ($request->has('pasienId')) {
                $pasienId = Pasien::where('no_rm', $request->pasienId)->value('id');
            }
            // dd($pasienId);
            $data = Pemeriksaan::where('pemeriksaan_idPasien', $pasienId)
                            ->get();
            return datatables()::of($data)
                    ->editColumn('pemeriksaan_rekTreatment', function($row) {
                        return $row->pemeriksaan_rekTreatment ?? '-';
                    })
                    ->editColumn('pemeriksaan_kdPendaftaran', function($row) {
                        return Pendaftaran::find($row->pemeriksaan_kdPendaftaran)->value('no_pendaftaran') ?? '-';
                    })
                    ->editColumn('created_at', function($row) {
                        return $row->created_at->format('Y-m-d H:i:s');
                    })
                    ->editColumn('pemeriksaan_rekSkincare', function($row) {
                        $rekSkincare = json_decode($row->pemeriksaan_rekSkincare, true);
                        return is_array($rekSkincare) ? implode(', ', $rekSkincare) : ($row->pemeriksaan_rekSkincare ?? '-');
                    })
                    ->editColumn('pemeriksaan_note', function($row) {
                        return $row->pemeriksaan_note ?? '-';
                    })
                    ->make(true);
        }

        return view('pages.data.listriwayatpasien', ['title' => "Data Pendaftaran Pasien"]);
    }

    public function show($id, Request $request) {
        if ($request->ajax()) {
            $pasienId = null;
            if ($id) {
                $pasienId = Pasien::where('no_rm', $id)->value('id');
            }
            // dd($pasienId);
            $data = Pemeriksaan::where('pemeriksaan_idPasien', $pasienId)
                            ->get();
            return datatables()::of($data)
                    ->editColumn('pemeriksaan_rekTreatment', function($row) {
                        return $row->pemeriksaan_rekTreatment ?? '-';
                    })
                    ->editColumn('pemeriksaan_kdPendaftaran', function($row) {
                        return Pendaftaran::find($row->pemeriksaan_kdPendaftaran)->value('no_pendaftaran') ?? '-';
                    })
                    ->editColumn('created_at', function($row) {
                        return $row->created_at->format('Y-m-d H:i:s');
                    })
                    ->editColumn('pemeriksaan_rekSkincare', function($row) {
                        $rekSkincare = json_decode($row->pemeriksaan_rekSkincare, true);
                        return is_array($rekSkincare) ? implode(', ', $rekSkincare) : ($row->pemeriksaan_rekSkincare ?? '-');
                    })
                    ->editColumn('pemeriksaan_note', function($row) {
                        return $row->pemeriksaan_note ?? '-';
                    })
                    ->make(true);
        }
    }

   
}
