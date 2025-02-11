<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $form;
    protected $title;

    public function __construct()
    {
        $this->title = 'User';

        $this->form = array(
            array(
                'label' => 'Nama User',
                'field' => 'name',
                'type' => 'text',
                'placeholder' => 'Masukan Nama',
                'required' => true
            ),
            array(
                'label' => 'Email',
                'field' => 'email',
                'type' => 'email',
                'placeholder' => 'Masukan Email',
                'required' => true

            ),
            array(
                'label' => 'Role',
                'field' => 'role',
                'type' => 'select',
                'placeholder' => 'Pilih Role',
                'required' => true,
                'options' => Roles::all()->pluck('role_name', 'role_id')->toArray()
            )


        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::all();
            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-sm editProduct"><i class="fa-regular fa-pen-to-square"></i> Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-outline-danger btn-sm deleteProduct"><i class="fa-solid fa-trash"></i> Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.users', ['form' => $this->form, 'title' => $this->title]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $password = Hash::make('password');

        $user = User::updateOrCreate(
            ['id' => $request->id],
            ['name' => $request->name, 'email' => $request->email, 'password' => $password, 'role_id' => $request->role]
        );

        $this->storeRiwayat(Auth::user()->id, "User", "INSERT", json_encode($user));

        return response()->json(['success' => 'User berhasil disimpan.']);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    // Fungsi untuk mengupdate data yang telah diedit
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role
        ]);

        $this->storeRiwayat(Auth::user()->id, "User", "UPDATE", json_encode($user));


        return response()->json(['success' => 'User updated successfully.']);
    }

    // Fungsi untuk menghapus data
    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }

    public function reset_password(Request $request)
    {
        $id = $request->input('id');
    $user = User::find($id);
    if ($user) {
        $user->password = bcrypt('password');
        $user->save();

        $this->storeRiwayat(Auth::user()->id, "User", "RESET PASSWORD", json_encode($user));

        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false]);
    }
}
}
