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
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            
            // Kolom Identitas Berkas
            $table->string('nama_berkas')->nullable();
            $table->text('deskripsi_berkas')->nullable();
            
            // Kolom Atribut (Semua String agar fleksibel terhadap input Excel)
            $table->string('jumlah_berkas')->nullable(); 
            $table->string('warna_berkas')->nullable();
            $table->string('tahun_berkas')->nullable();
            
            // Kolom Relasi & Kode (Panjang 255 karakter otomatis)
            $table->string('kode_arsip')->nullable();
            
            // WAJIB: Kolom untuk mencatat siapa yang mengupload/menginput data
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};