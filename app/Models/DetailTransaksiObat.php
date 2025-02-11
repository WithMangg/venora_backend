<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksiObat extends Model
{
    protected $table = 'detail_transaksi_obat';

    protected $fillable = [
        'transaksi_id',
        'obatId',
        'jumlah'
    ];
}
