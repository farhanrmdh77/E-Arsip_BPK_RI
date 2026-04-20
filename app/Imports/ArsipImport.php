<?php

namespace App\Imports;

use App\Models\Arsip;
use App\Models\Folder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;

class ArsipImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 🌟 1. SATPAM LAPIS PERTAMA: Validasi Kesesuaian Template Excel 🌟
        if (!array_key_exists('kode_arsip', $row) || !array_key_exists('nama_berkas', $row)) {
            throw new Exception("Salah Kamar! 🛑 Dokumen Excel yang Anda unggah BUKAN format rekap E-Arsip. Pastikan baris pertama memiliki judul kolom 'nama_berkas' dan 'kode_arsip'.");
        }

        // 2. Abaikan baris jika isinya kosong
        if (empty($row['kode_arsip']) || empty($row['nama_berkas'])) {
            return null;
        }

        $kodeArsip = strtoupper(trim($row['kode_arsip']));
        
        // 🔥 3. PERBAIKAN LOGIKA EKSTRAK KODE (ANTI-BUG LAINNYA.00) 🔥
        // Mengecek apakah kode mengandung tanda titik (.)
        if (strpos($kodeArsip, '.') !== false) {
            $folderIndukArr = explode('.', $kodeArsip);
            $kodeInduk = $folderIndukArr[0] . '.' . ($folderIndukArr[1] ?? '00');
        } else {
            // Jika tidak ada titik (misal: LAINNYA), maka kode induk = kode arsip itu sendiri
            $kodeInduk = $kodeArsip; 
        }

        // 🌟 4. SATPAM LAPIS KEDUA: Validasi Keberadaan Brankas 🌟
        $cekFolder = Folder::where('kode_folder', $kodeInduk)->exists();

        if (!$cekFolder) {
            throw new Exception("Ada dokumen nyasar! 🛑 Dokumen '{$row['nama_berkas']}' menggunakan kode '{$kodeArsip}', tetapi Brankas '{$kodeInduk}' belum terdaftar di Gudang Folder. Buat foldernya dulu!");
        }

        // 5. LOGIKA EKSTRAKSI TAHUN SISTEM UNTUK JRA
        $tahunBerkas = $row['tahun_berkas'] ?? date('Y');
        preg_match('/\d{4}/', $tahunBerkas, $matches);
        $tahunSistem = !empty($matches) ? (int)$matches[0] : (int)date('Y');

        // 6. Masukkan ke Database
        return new Arsip([
            'nama_berkas'      => $row['nama_berkas'],
            'kode_arsip'       => $kodeArsip, // Akan masuk sebagai 'LAINNYA'
            'tahun_berkas'     => $tahunBerkas,
            'tahun_sistem'     => $tahunSistem, 
            'jumlah_berkas'    => $row['jumlah_berkas'] ?? 1,
            'warna_berkas'     => $row['warna_berkas'] ?? '-',
            'deskripsi_berkas' => $row['deskripsi_berkas'] ?? null,
            'retensi_aktif'    => $row['retensi_aktif'] ?? 0,    
            'retensi_inaktif'  => $row['retensi_inaktif'] ?? 0,  
            'nasib_akhir'      => $row['nasib_akhir'] ?? 'Musnah',
            'user_id'          => auth()->id() ?? 1,
        ]);
    }
}