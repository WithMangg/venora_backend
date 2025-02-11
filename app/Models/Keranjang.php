<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'data_keranjangobat';

    protected $fillable = [
        'no_pendaftaran', 'pasienId', 'obatId', 'jumlah'
    ];
}
