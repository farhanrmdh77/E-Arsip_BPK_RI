<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('arsips', function (Blueprint $table) {
        $table->integer('tahun_sistem')->nullable()->after('tahun_berkas'); // Untuk perhitungan matematika (int)
        $table->integer('retensi_aktif')->default(0)->after('jumlah_berkas'); // Contoh: 2 (Tahun)
        $table->integer('retensi_inaktif')->default(0)->after('retensi_aktif'); // Contoh: 3 (Tahun)
        $table->string('nasib_akhir')->default('Musnah')->after('retensi_inaktif'); // Musnah / Permanen
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsips', function (Blueprint $table) {
            //
        });
    }
};
