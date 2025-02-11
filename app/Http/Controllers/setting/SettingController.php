<?php

namespace App\Http\Controllers\setting;

use App\Http\Controllers\Controller;
use App\Models\SettingWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    private $title = 'Setting Web';
    private $form;

    public function __construct()
    {
        $this->form = array(
            array(
                'label' => 'Favicon',
                'field' => 'favicon',
                'type' => 'file',
                'placeholder' => '',
                'width' => 6,
                'required' => true,
            ),
            array(
                'label' => 'Logo',
                'field' => 'logo',
                'type' => 'file',
                'placeholder' => 'Masukkan logo',
                'width' => 6,
                'required' => true,
            ),
            array(
                'label' => 'Description',
                'field' => 'description',
                'type' => 'textarea',
                'placeholder' => 'Masukkan description',
                'width' => 12,
            ),
            array(
                'label' => 'Site Name',
                'field' => 'site_name',
                'type' => 'text',
                'placeholder' => 'Masukkan site name',
                'width' => 6,
                'required' => true,
            ),
            array(
                'label' => 'Email',
                'field' => 'email',
                'type' => 'text',
                'placeholder' => 'Masukkan email',
                'width' => 6,
            ),
            array(
                'label' => 'Phone',
                'field' => 'phone',
                'type' => 'text',
                'placeholder' => 'Masukkan phone',
                'width' => 6,
            ),
            array(
                'label' => 'Address',
                'field' => 'address',
                'type' => 'text',
                'placeholder' => 'Masukkan address',
                'width' => 6,
            ),
        );
    }

    public function index()
    {
        return view('pages.setting.settings', [
            'title' => $this->title,
            'form' => $this->form
        ]);
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'site_name' => 'required',
        'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $data = SettingWeb::find('1');

    $faviconBase64 = $data->favicon;
    if ($request->hasFile('favicon')) {
        $faviconBase64 = base64_encode(file_get_contents($request->file('favicon')->getRealPath()));
    }

    $logoBase64 = $data->logo;
    if ($request->hasFile('logo')) {
        $logoBase64 = base64_encode(file_get_contents($request->file('logo')->getRealPath()));
    }

    // Memperbarui pengaturan website
    $web = SettingWeb::UpdateOrCreate(['id' => '1'],[
            'favicon' => $faviconBase64,
            'logo' => $logoBase64,
            'description' => $request->input('description'),
            'site_name' => $request->site_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $this->storeRiwayat(Auth::user()->id, "settingweb", "UPDATE", json_encode($web));


        return response()->json(['success' => 'Pengaturan Web berhasil disimpan.']);

}

}
