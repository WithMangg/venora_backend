<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    protected $table = 'msdiagnosa';

    protected $primaryKey = 'id';
    protected $fillable = ['kd_diagnosa', 'diagnosa', 'kategori', 'harga'];
}
