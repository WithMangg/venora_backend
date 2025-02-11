<?php

namespace App\Http\Controllers\setting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\RoleMenu;
use App\Models\Roles;
use Illuminate\Http\Request;

class MenuRoleController extends Controller
{
    protected $title ;
    protected $form;

    public function __construct()
    {
        $this->title = 'Menu';

        $this->form = array(
            array(
                'label' => 'Nama Role',
                'field' => 'role_id',
                'type' => 'select',
                'options' => Roles::all()->pluck('role_name', 'role_id')->toArray(),
                'placeholder' => 'Masukan Nama Menu',
                'required' => true
            ),
            array(
                'label' => 'Menu',
                'field' => 'menu_id',
                'type' => 'checkbox',
                'options' => Menu::whereNotNull('parent_id')->pluck('name', 'id')->toArray(),
                'placeholder' => 'Masukan Menu',
                'required' => true,
                'checkbox' => true
            ),
        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = RoleMenu::all();
            return datatables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-outline-primary btn-sm editProduct"><i class="fa-regular fa-pen-to-square"></i> Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-danger btn-sm deleteProduct"><i class="fa-solid fa-trash"></i> Delete</a>';
                        
                            return $btn;
                    })
                    ->editColumn('role_id', function($row) {
                        return Roles::where('role_id', $row->role_id)->first()->role_name;
                    })
                    ->make(true);
        }

        return view('pages.setting.rolemenus', ['title' => $this->title, 'form' => $this->form]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
            'menu_id' => 'required|array',
        ]);

        $menus_id = json_encode($request->menu_id);

        RoleMenu::create(
            ['role_id' => $request->role_id, 'menu_id' => $menus_id]
        );


        return response()->json(['success' => 'Data berhasil disimpan']);
    }


    public function edit($id)
    {
        $data = RoleMenu::find($id);
        $data->menu_id = json_decode($data->menu_id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required',
            'menu_id' => 'required|array',
        ]);

        $menus_id = json_encode($request->menu_id);

        $data = RoleMenu::find($id);
        $data->update([
            'role_id' => $request->role_id, 'menu_id' => $menus_id
        ]);

        return response()->json(['success' => 'Data berhasil disimpan']);
    }
}
