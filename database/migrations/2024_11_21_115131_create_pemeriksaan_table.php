<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kd_diagnosa');
            $table->string('kd_pendaftaran');
            $table->string('pasien_id');
            $table->date('tanggal_diagnosa');
            $table->text('keluhan_utama')->nullable();
            $table->text('riwayat_penyakit_sekarang')->nullable();
            $table->string('tinggi_badan')->nullable();
            $table->string('berat_badan')->nullable();
            $table->string('tekanan_darah')->nullable();
            $table->string('suhu_tubuh')->nullable();
            $table->string('nadi')->nullable();
            $table->string('frekuensi_napas')->nullable();
            $table->string('diagnosis_utama')->nullable();
            $table->string('diagnosis_pendukung')->nullable();
            $table->text('tindakan_medis')->nullable();
            $table->text('resep_obat')->nullable();
            $table->string('jumlah_obat')->nullable();
            
            $table->text('konsultasi_lanjutan')->nullable();
            $table->string('rujukan')->default('Tidak');
            $table->text('anjuran_dokter')->nullable();
            $table->string('status_pulang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
