<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';

    protected $fillable = [
        'no_pembayaran',
        'no_pemeriksaan',
        'pasien_id',
        'dokter',
        'tanggal_pemeriksaan',
        'treatment',
        'skincare',
        'jumlahSkincare',
        'total',
        'bayar',
        'kembali',
        'status',
    ];
}
