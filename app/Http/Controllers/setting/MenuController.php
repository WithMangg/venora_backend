<?php

namespace App\Http\Controllers\setting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $title ;
    protected $form;

    public function __construct()
    {
        $this->title = 'Menu';

        $this->form = array(
            array(
                'label' => 'Nama Menu',
                'field' => 'name',
                'type' => 'text',
                'placeholder' => 'Masukan Nama Menu',
                'required' => true
            ),
            array(
                'label' => 'Route',
                'field' => 'route',
                'type' => 'text',
                'placeholder' => 'Masukan Route',
                'required' => true
            ),
            array(
                'label' => 'Icon',
                'field' => 'icon',
                'type' => 'textarea',
                'placeholder' => 'Masukan Icon',
                'required' => false
            ),
            array(
                'label' => 'Parent Id',
                'field' => 'parent_id',
                'type' => 'select',
                'options' => Menu::whereNull('parent_id')->pluck('name', 'id')->toArray(),
                'placeholder' => 'Masukan parent_id',
                'required' => false
            ),
            array(
                'label' => 'Menu Order',
                'field' => 'menu_order',
                'type' => 'number',
                'placeholder' => 'Masukan menu_order',
                'required' => false
            )
        );
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Menu::all();
            return datatables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-outline-primary btn-sm editProduct"><i class="fa-regular fa-pen-to-square"></i> Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-danger btn-sm deleteProduct"><i class="fa-solid fa-trash"></i> Delete</a>';
                        
                            return $btn;
                    })
                    ->editColumn('parent_id', function($row) {
                        if ($row->parent_id == null) {
                            return 'Root';
                        } else {
                            return $row->parent_id . ' - ' . optional(Menu::find($row->parent_id))->name;
                        }
                    })
                    ->make(true);
        }

        return view('pages.setting.menus', ['title' => $this->title, 'form' => $this->form]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        Menu::create($data);
        return response()->json(['success' => 'Menu created successfully.']);
    }

    public function edit($id)
    {
        $data = Menu::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $menu = Menu::findOrFail($id);
        $menu->update($data);
        return response()->json(['success' => 'Menu updated successfully.']);
    }

    
}
