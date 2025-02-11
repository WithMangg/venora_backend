<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPoli extends Model
{
    protected $table = 'data_poli';

    protected $fillable = [
        'poli_id',
        'dokter_id',
    ];
}
