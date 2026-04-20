<?php

namespace App\Exports;

use App\Models\Arsip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class ArsipExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    /**
     * Menangkap data rentang tanggal yang dikirim dari Controller
     */
    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Mengambil data arsip dari database (dengan filter waktu jika ada)
     */
    public function collection()
    {
        $query = Arsip::query();

        // Jika form rentang waktu diisi, saring berdasarkan tanggal masuk (created_at)
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        // Tetap urutkan berdasarkan kode arsip (KP) seperti aslinya
        return $query->orderBy('kode_arsip', 'asc')->get();
    }

    /**
     * Memetakan data apa saja yang masuk ke kolom Excel
     */
    public function map($arsip): array
    {
        return [
            $arsip->kode_arsip,
            $arsip->nama_berkas,
            $arsip->deskripsi_berkas,
            $arsip->tahun_berkas,
            $arsip->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i'),
        ];
    }

    /**
     * Membuat Baris Pertama (Judul Kolom) di Excel
     */
    public function headings(): array
    {
        return [
            'Kode Folder (KP)',
            'Nama Dokumen',
            'Keterangan / Deskripsi',
            'Tahun Berkas',
            'Waktu Diunggah ke Sistem',
        ];
    }
}