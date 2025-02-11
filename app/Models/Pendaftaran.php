<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'data_pendaftaran';

    protected $fillable = [
        'poli_id',
        'dokter_id',
        'no_antrian',
        'no_pendaftaran',
        'pasien_id',
        'tanggal_daftar',
        'keluhan',
        'status'
    ];
}
