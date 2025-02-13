<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skincare extends Model
{
    protected $table = 'msskincare';

    protected $fillable = [
        'skincare_id',
        'skincare_nama',
        'skincare_brand',
        'skincare_kategori',
        'skincare_harga',
        'skincare_stok',
        'skincare_penggunaan',
        'skincare_deskripsi',
        'foto'
    ];
}
