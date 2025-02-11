<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'msobat';

    protected $fillable = [
        'medicine_id',
        'nama_obat',
        'nama_generik',
        'kategori',
        'bentuk_dosis',
        'kekuatan',
        'harga',
        'jumlah_stok',
        'tanggal_kedaluwarsa',
        'produsen',
        'instruksi_penggunaan',
        'efek_samping',
        'foto',
        'instruksi_penyimpanan',
    ];
}
