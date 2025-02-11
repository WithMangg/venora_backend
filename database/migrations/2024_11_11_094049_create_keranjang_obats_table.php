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
        Schema::create('data_keranjangobat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_pendaftaran')->nullable();
            $table->string('pasienId');
            $table->string('obatId');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_keranjangobat');
    }
};
