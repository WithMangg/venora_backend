<?php

namespace App\Http\Controllers\setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingPenggunaController extends Controller
{
    private $title = 'Setting Pengguna';
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
        return view('pages.setting.settingpengguna', [
            'title' => $this->title,
            'form' => $this->form
        ]);
    }
}
