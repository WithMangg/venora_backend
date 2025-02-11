<?php

namespace App\Http\Controllers\layanan;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\TransaksiObat;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    protected $title;
    
    public function __construct()
    {
        $this->title = "Transaksi";
    }

    public function index(Request $request)
    {   
        if ($request->ajax()) {
            $data = TransaksiObat::all();
            return datatables()::of($data)
                    ->addIndexColumn()
                    ->editColumn('pasienId', function($row) {
                        return Pasien::where('no_rm', $row->pasienId)->first()->nama_pasien;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('pages.layanans.transaksi.transaksis', ['title' => $this->title]);
    }
}
