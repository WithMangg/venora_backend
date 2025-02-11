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
        Schema::create('mskamar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipe_kamar');
            $table->string('fasilitas');
            $table->string('tarif_kamar');
            $table->string('jumlah_kamar');

            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mskamar');
    }
};
