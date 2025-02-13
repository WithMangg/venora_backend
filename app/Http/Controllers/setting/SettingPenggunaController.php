<?php

namespace App\Http\Controllers\setting;

use App\Http\Controllers\Controller;
use App\Models\Riwayat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingPenggunaController extends Controller
{
    private $title = 'Setting Personal';
    private $form;

    public function __construct()
    {
       
    }

    public function index(Request $request)
    {
        if(Auth::user()->role_id) {
            $model = User::find(Auth::user()->id);
        }
        
        if ($request->ajax()) {
            $data = Riwayat::where('user_id', Auth::user()->id)->where('table', 'USERS')->get();
            return datatables()::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_at', function ($row) {
                        return date('d-m-Y H:i:s', strtotime($row->created_at));
                    })
                    ->editColumn('aksi', function ($row) {
                        return '<span class="badge bg-primary text-white">'. $row->aksi .'</span>';
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
        }

        // dd($model);

        return view('pages.setting.settingpengguna', [
            'title' => $this->title,
            'form' => $this->form,
            'model' => $model
        ]);
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'old_password' => 'nullable|string|min:8',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $model = User::find($request->id);
        if ($request->old_password) {
            if (!Hash::check($request->old_password, $model->password)) {
                return response()->json(['errors' => ['old_password' => ['Password lama salah']]], 422);
            }
            $model->password = Hash::make($request->password);
        }

        if ($model->save()) {
            $this->storeRiwayat(Auth::user()->id, "USERS", "UPDATE", 'Telah dilakukan perubahan Password');
            return response()->json(['success' => 'Pengaturan Pengguna Berhasil diperbarui!']);

        }


        return response()->json(['errors' => ['Gagal update data']], 500);
    }
}
