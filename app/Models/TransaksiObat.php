<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiObat extends Model
{
    protected $table = 'transaksi_obat';

    protected $fillable = [
        'transaksi_id',
        'pasienId',
        'total',
        'diskon',
        'bayar',
        'tanggal_transaksi',
        'status',
    ];
    
}
