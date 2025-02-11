<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTindakanMedis extends Model
{
    protected $table = 'detail_tindakan_medis';

    protected $fillable = [
        'tindakan_medis_id',
        'tindakan_medis',
        'harga',
    ];
}
