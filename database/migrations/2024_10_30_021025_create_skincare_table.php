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
        Schema::create('msskincare', function (Blueprint $table) {
            $table->increments('id');
            $table->string('skincare_id');
            $table->longText('foto')->nullable();
            $table->string('skincare_nama');
            $table->string('skincare_brand');
            $table->string('skincare_kategori');
            $table->integer('skincare_harga');
            $table->integer('skincare_stok');
            $table->text('skincare_penggunaan');
            $table->text('skincare_deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msskincare');
    }
};
