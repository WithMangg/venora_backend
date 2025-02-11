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
        Schema::create('msdokter', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kd_dokter');
            $table->string('nik');
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('email')->unique();
            $table->string('spesialisasi')->nullable();
            $table->string('no_hp')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jk')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('agama')->nullable();
            $table->string('pendidikan')->nullable();
            $table->mediumText('image')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msdokter');
    }
};
