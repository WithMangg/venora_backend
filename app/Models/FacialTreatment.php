<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacialTreatment extends Model
{
    protected $table = 'msfacial_treatments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'facialTreatment_id',
        'facialTreatment_nama',
        'facialTreatment_harga',
        'facialTreatment_durasi',
        'facialTreatment_deskripsi',
        'facialTreatment_benefit',
        'facialTreatment_foto',
    ];
}
