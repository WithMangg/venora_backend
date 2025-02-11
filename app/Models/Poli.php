<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $table = 'mspoli';

    protected $fillable = [
        'kd_poli',
        'nama_poli',
        'deskripsi'
    ];
}
