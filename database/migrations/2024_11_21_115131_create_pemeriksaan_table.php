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
            $table->integer('pemeriksaan_idPendaftaran')->unsigned()->index();
            $table->foreign('pemeriksaan_kdPendaftaran')->references('id')->on('data_pendaftaran')->onDelete('cascade');

            $table->unsignedInteger('pemeriksaan_idPasien')->index();
            $table->foreign('pemeriksaan_idPasien')->references('id')->on('mspasien')->onDelete('cascade');

            $table->string('pemeriksaan_keluhan');
            $table->string('pemeriksaan_kondisiKulit');
            $table->string('pemeriksaan_diagnosis');
            $table->string('pemeriksaan_rekTreatment')->nullable();            
            $table->text('pemeriksaan_rekSkincare')->nullable();
            $table->text('pemeriksaan_jumlahSkincare')->nullable();

            $table->string('pemeriksaan_note')->nullable();
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
