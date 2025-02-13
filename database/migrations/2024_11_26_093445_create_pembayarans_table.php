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
            $table->integer('no_pemeriksaan');
            $table->unsignedInteger('pasien_id');
            $table->foreign('pasien_id')->references('id')->on('mspasien')->onDelete('cascade');
            $table->string('dokter');
            $table->string('tanggal_pemeriksaan');
            $table->text('treatment')->nullable();
            $table->text('skincare')->nullable();
            $table->text('jumlahSkincare')->nullable();
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
