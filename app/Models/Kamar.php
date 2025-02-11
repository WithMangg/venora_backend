<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'mskamar';

    protected $fillable = [
        'tipe_kamar',
        'fasilitas',
        'tarif_kamar',
        'jumlah_kamar',
        'status',
    ];
}
