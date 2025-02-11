<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingWeb extends Model
{
    protected $table = 'mssetting_web';

    protected $fillable = [
        'favicon',
        'logo',
        'title',
        'description',
        'site_name',
        'email',
        'phone',
        'address',
    ];
}
