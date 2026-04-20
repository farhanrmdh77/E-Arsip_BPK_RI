<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Arsip extends Model
{
    use HasFactory, SoftDeletes; 

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'nama_berkas',
        'deskripsi_berkas',
        'jumlah_berkas',
        'warna_berkas',
        'tahun_berkas',
        'tahun_sistem', 
        'kode_arsip',
        'file_dokumen', 
        'user_id',
        'retensi_aktif',
        'retensi_inaktif',
        'nasib_akhir',
        'status_lokasi',
    ];
}