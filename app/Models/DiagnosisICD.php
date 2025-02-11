<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisICD extends Model
{
    protected $table = 'diagnosis_icd';

    public $timestamps = false;

    protected $fillable = [
        'code', 'name_en', 'name_id',
    ];
}
