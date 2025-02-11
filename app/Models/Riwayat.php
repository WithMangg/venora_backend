<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    protected $table = 'msriwayat';

    protected $fillable = [
        'user_id',
        'table',
        'aksi',
        'keterangan',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
