<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaan';

    protected $fillable = [
        'kd_diagnosa',
        'pasien_id',
        'kd_pendaftaran',
        'tanggal_diagnosa',
        'keluhan_utama',
        'riwayat_penyakit_sekarang',
        'tinggi_badan',
        'berat_badan',
        'tekanan_darah',
        'suhu_tubuh',
        'nadi',
        'frekuensi_napas',
        'diagnosis_utama',
        'diagnosis_pendukung',
        'tindakan_medis',
        'resep_obat',
        'jumlah_obat',
        'konsultasi_lanjutan',
        'rujukan',
        'anjuran_dokter',
        'status_pulang',
    ];
    
}
