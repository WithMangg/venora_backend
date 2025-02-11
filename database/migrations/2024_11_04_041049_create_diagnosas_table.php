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
        Schema::create('msdiagnosa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kd_diagnosa');
            $table->string('diagnosa');
            $table->string('kategori')->nullable();
            $table->double('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msdiagnosa');
    }
};
