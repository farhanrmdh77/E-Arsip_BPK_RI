<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('arsips', function (Blueprint $table) {
        // Menambahkan kolom file_dokumen setelah kode_arsip
        $table->string('file_dokumen')->nullable()->after('kode_arsip');
    });
}

public function down(): void
{
    Schema::table('arsips', function (Blueprint $table) {
        $table->dropColumn('file_dokumen');
    });
}
};
