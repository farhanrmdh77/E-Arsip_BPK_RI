<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Folder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class TrashController extends Controller
{
    public function index()
    {
        // 🔥 LOGIKA AUTO-CLEANUP (30 HARI) 🔥
        $batasWaktu = Carbon::now()->subDays(30);

        // 1. Eksekusi Hapus Arsip Kedaluwarsa (Termasuk file fisiknya)
        $arsipKedaluwarsa = Arsip::onlyTrashed()->where('deleted_at', '<', $batasWaktu)->get();
        foreach($arsipKedaluwarsa as $arsip) {
            if ($arsip->file_dokumen) {
                Storage::disk('public')->delete($arsip->file_dokumen);
            }
            $arsip->forceDelete();
        }

        // 2. Eksekusi Hapus Folder Kedaluwarsa
        $folderKedaluwarsa = Folder::onlyTrashed()->where('deleted_at', '<', $batasWaktu)->get();
        foreach($folderKedaluwarsa as $folder) {
            $folder->forceDelete();
        }
        // 🔥 AKHIR LOGIKA AUTO-CLEANUP 🔥

        // Ambil data yang masih aman (belum 30 hari) untuk ditampilkan di tabel
        $arsips = Arsip::onlyTrashed()->latest('deleted_at')->get();
        $folders = Folder::onlyTrashed()->latest('deleted_at')->get();

        return view('arsip.trash', compact('arsips', 'folders'));
    }

    // ==========================================
    // 🌟 BAGIAN 1: KELOLA ARSIP TERHAPUS 🌟
    // ==========================================
    
    public function restoreArsip($id)
    {
        $arsip = Arsip::onlyTrashed()->findOrFail($id);
        $arsip->restore();
        
        return back()->with('success', 'Arsip "' . $arsip->nama_berkas . '" berhasil dipulihkan!');
    }

    public function forceDeleteArsip($id)
    {
        $arsip = Arsip::onlyTrashed()->findOrFail($id);
        
        // Hapus file fisik dari folder storage jika ada
        if ($arsip->file_dokumen) {
            Storage::disk('public')->delete($arsip->file_dokumen);
        }
        
        $arsip->forceDelete();
        
        return back()->with('success', 'Arsip beserta file fisiknya berhasil dimusnahkan secara permanen!');
    }

    // ==========================================
    // 🌟 BAGIAN 2: KELOLA FOLDER TERHAPUS 🌟
    // ==========================================

    public function restoreFolder($id)
    {
        $folder = Folder::onlyTrashed()->findOrFail($id);
        $folder->restore();
        
        return back()->with('success', 'Folder "' . $folder->kode_folder . '" berhasil dipulihkan!');
    }

    public function forceDeleteFolder($id)
    {
        $folder = Folder::onlyTrashed()->findOrFail($id);
        $folder->forceDelete();
        
        return back()->with('success', 'Folder berhasil dimusnahkan secara permanen!');
    }
}