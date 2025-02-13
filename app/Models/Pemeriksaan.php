<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaan';

    protected $fillable = [
        'pemeriksaan_kdPendaftaran',
        'pemeriksaan_idPasien',
        'pemeriksaan_keluhan',
        'pemeriksaan_kondisiKulit',
        'pemeriksaan_diagnosis',
        'pemeriksaan_rekTreatment',
        'pemeriksaan_rekSkincare',
        'pemeriksaan_jumlahSkincare',
        'pemeriksaan_note',
    ];
    
}
