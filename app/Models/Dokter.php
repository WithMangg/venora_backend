<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'msdokter';
    protected $fillable = [
        'kd_dokter',
        'nik',
        'nama',
        'alamat',
        'email',
        'spesialisasi',
        'no_hp',
        'tanggal_lahir',
        'jk',
        'pekerjaan',
        'kewarganegaraan',
        'agama',
        'pendidikan',
        'image',
        'status',
    ];
}
