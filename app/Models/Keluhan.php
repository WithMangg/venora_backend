<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    protected $table = 'mskeluhan';

    protected $fillable = [
        'user_id',
        'keluhan',
        'foto',
    ];
}
