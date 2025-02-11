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
        Schema::create('mspasien', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_rm');
            $table->string('nik');
            $table->string('nama_pasien');
            $table->string('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jk')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('agama')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mspasien');
    }
};
