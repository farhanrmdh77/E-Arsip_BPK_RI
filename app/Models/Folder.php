<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Pastikan ini diimpor

class Folder extends Model
{
    use HasFactory, SoftDeletes; // Tambahkan SoftDeletes di sini

    /**
     * Kolom yang diizinkan untuk pengisian massal (Mass Assignment).
     * Ini mencegah error MassAssignmentException yang kamu alami sebelumnya.
     */
    protected $fillable = [
        'kode_folder', 
        'nama_folder'
    ];
}