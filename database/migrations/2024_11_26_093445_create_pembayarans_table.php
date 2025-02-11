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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_pembayaran');
            $table->string('no_diagnosa');
            $table->string('no_rm');
            $table->string('nama_pasien');
            $table->string('poli');
            $table->string('dokter');
            $table->string('tanggal_pemeriksaan');
            $table->string('tindakan_medis')->nullable();
            $table->string('resep_obat')->nullable();
            $table->string('jumlah_obat')->nullable();
            $table->string('total')->nullable();
            $table->string('bayar')->nullable();
            $table->string('kembali')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
