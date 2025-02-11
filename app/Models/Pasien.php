<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'mspasien';

    protected $primaryKey = 'id';
    protected $fillable = [
        'no_rm',
        'nik',
        'nama_pasien',
        'alamat',
        'email',
        'no_hp',
        'tanggal_lahir',
        'jk',
        'pekerjaan',
        'kewarganegaraan',
        'agama',
        'pendidikan',
        'status'
    ];
}
