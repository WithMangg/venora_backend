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
        Schema::create('msfacial_treatments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('facialTreatment_id');
            $table->string('facialTreatment_nama');
            $table->string('facialTreatment_harga');
            $table->integer('facialTreatment_durasi');
            $table->text('facialTreatment_deskripsi')->nullable();
            $table->text('facialTreatment_benefit')->nullable();
            $table->longText('facialTreatment_foto')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msfacial_treatments');
    }
};
