<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Data\DataPoliController;
use App\Http\Controllers\Data\DataRekamMedisController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\layanan\CekPendaftaranController;
use App\Http\Controllers\layanan\PemeriksaanPasienController;
use App\Http\Controllers\layanan\PendaftaranController;
use App\Http\Controllers\layanan\TransaksiController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\Pembayaran\PembayaranController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\setting\MenuController;
use App\Http\Controllers\setting\MenuRoleController;
use App\Http\Controllers\setting\RiwayatController;
use App\Http\Controllers\setting\SettingController;
use App\Http\Controllers\setting\SettingPenggunaController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserRole;
use App\Models\Pendaftaran;

Route::get('/', function () {
    return view('pages.dashboard');
})->name('dashboard')->middleware(['auth', 'verified']);

Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');

Route::get('/scan', function () {
    return view('scan');
});


Route::get('api/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->middleware('throttle:5,1');

Route::post('api/login', [ApiController::class, 'login'])
    ->name('api.login');

Route::post('/api/tambah-obat', [ApiController::class, 'tambahObat'])->name('api.tambahObat');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('api/getKamar', [ApiController::class, 'getKamar'])->name('kamars.getkamars');
    Route::get('api/getDokter', [ApiController::class, 'getDokter'])->name('dokters.getdokter');
    Route::get('api/getObat', [ApiController::class, 'getObat'])->name('obats.getobat');
    Route::post('api/send-data-pendaftaran', [ApiController::class, 'sendDataPendaftaran'])->name('api.senddatapendaftaran');
    Route::get('/api/user', [ApiController::class, 'getUserData']);
    Route::get('api/get-riwayat-pendaftaran/{no_rm}', [ApiController::class, 'getRiwayatPendaftaran'])->name('api.getriwayatpendaftaran');
    Route::post('api/refresh-token', [ApiController::class, 'refreshToken'])->name('api.refreshtoken');
    Route::post('api/logout', [ApiController::class, 'logout'])->name('api.logout');
    Route::post('api/send-keluhan', [ApiController::class, 'sendKeluhan'])->name('api.sendkeluhan');
    Route::get('api/get-data-keranjang/{no_rm}', [ApiController::class, 'getDataKeranjang'])->name('api.getdatakeranjang');
    Route::post('api/transaksi-obat', [ApiController::class, 'transaksiObat'])->name('api.transaksiobat');
    
});

Route::group(['middleware' => ['auth', 'CheckUserRole']], function () {
    Route::resource('masters/pasiens', PasienController::class);

    
    Route::resource('masters/kamars', KamarController::class);
    Route::resource('masters/users', UserController::class);

    Route::resource('masters/dokters', DokterController::class);

    Route::resource('masters/obats', ObatController::class);

    Route::resource('masters/polis', PoliController::class);

    Route::resource('data/datapolis', DataPoliController::class);
    Route::resource('data/datarekammedis', DataRekamMedisController::class);

    Route::resource('setting/settings', SettingController::class);
    Route::resource('setting/setting-pengguna', SettingPenggunaController::class);
    Route::resource('setting/riwayats', RiwayatController::class);

    Route::resource('layanans/transaksis', TransaksiController::class);

    Route::resource('layanans/pendaftarans', PendaftaranController::class);
    Route::resource('layanans/cek-pendaftarans', CekPendaftaranController::class);
    Route::resource('layanans/pemeriksaan-pasien', PemeriksaanPasienController::class);
    Route::get('layanans/pemeriksaan-pasien/getDataDiagnosis/{pasien_id}', [PemeriksaanPasienController::class, 'getDataDiagnosis'])->name('layanans.pemeriksaan-pasien.getdatadiagnosis');


    Route::POST('layanans/cek-pendaftarans/getInfoPendaftaran', [CekPendaftaranController::class, 'getInfoPendaftaran'])->name('pendaftarans.cekpendaftaran.getinfopendaftaran');

    Route::post('users/reset-password', [UserController::class, 'reset_password'])->name('users.reset_password');
    Route::get('data/datapolis/getDokterByPoliId/{poliId}', [DataPoliController::class, 'getDokterByPoliId'])->name('data.datapolis.getDokterByPoliId');
    Route::get('data/data-pendaftarans', [PendaftaranController::class, 'getPendaftarans'])->name('pendaftarans.listpendaftarans');

    Route::resource('data/diagnosas', DiagnosaController::class);
    Route::POST('data/diagnosas/import', [DiagnosaController::class, 'import'])->name('diagnosas.import');
    Route::get('api/diagnosas/diagnosisutamas', [DiagnosaController::class, 'getOptions'])->name('api.diagnosas.diagnosisutamas');

    Route::resource('transaksi/pembayarans', PembayaranController::class);
    Route::get('transaksi/pembayarans/checked', [PembayaranController::class, 'index2'])->name('pembayarans.index2');

    Route::resource('setting/menus', MenuController::class);
    Route::resource('setting/menuroles', MenuRoleController::class);

    Route::get('masters/import', [ImportController::class, 'index'])->name('import.index');
    Route::post('masters/import/pasiens', [ImportController::class, 'importPasien'])->name('import.pasien');
    Route::post('masters/import/polis', [ImportController::class, 'importPoli'])->name('import.poli');
    Route::post('masters/import/kamars', [ImportController::class, 'importKamar'])->name('import.kamar');
    Route::post('masters/import/obats', [ImportController::class, 'importObat'])->name('import.obat');




});

require __DIR__.'/auth.php';





