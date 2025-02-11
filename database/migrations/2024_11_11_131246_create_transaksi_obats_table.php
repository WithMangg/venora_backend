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
        Schema::create('transaksi_obat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaksi_id');
            $table->string('pasienId');
            $table->double('total');
            $table->double('diskon')->nullable();
            $table->double('bayar')->nullable();
            $table->date('tanggal_transaksi');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_obat');
    }
};
