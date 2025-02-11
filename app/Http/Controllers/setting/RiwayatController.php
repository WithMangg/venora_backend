<?php

namespace App\Http\Controllers\setting;

use App\Http\Controllers\Controller;
use App\Models\Riwayat;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    private $title = 'Riwayat Aktivitas Pengguna';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Riwayat::all();
            return datatables()::of($data)
                    ->addIndexColumn()
                    ->make(true);
        }

        return view('pages.setting.riwayats', ['title' => $this->title]);
    }
}
