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
        Schema::create('msobat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('medicine_id');
            $table->string('nama_obat');
            $table->string('nama_generik');
            $table->string('kategori')->nullable();
            $table->string('bentuk_dosis');
            $table->string('kekuatan')->nullable();
            $table->decimal('harga', 10, 2);
            $table->integer('jumlah_stok');
            $table->date('tanggal_kedaluwarsa');
            $table->string('produsen');
            $table->text('instruksi_penggunaan');
            $table->text('efek_samping');
            $table->text('instruksi_penyimpanan');
            $table->mediumText('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msobat');
    }
};
