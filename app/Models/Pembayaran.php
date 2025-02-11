<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';

    protected $fillable = [
        'no_pembayaran',
        'no_diagnosa',
        'no_rm',
        'nama_pasien',
        'poli',
        'dokter',
        'tanggal_pemeriksaan',
        'tindakan_medis',
        'resep_obat',
        'jumlah_obat',
        
        'total',
        'bayar',
        'kembali',
        'status',
    ];
}
