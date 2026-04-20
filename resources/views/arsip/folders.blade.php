@extends('layouts.app')

@section('title', 'Gudang Folder - ' . \App\Models\Setting::getAppName())
@section('header_title', 'Gudang Klasifikasi Folder')

@push('styles')
<style>
    .custom-page { font-family: 'Poppins', sans-serif; color: #334155; padding-top: 10px; padding-bottom: 50px; }
    
    /* 🌟 1. PREMIUM DARK BANNER 🌟 */
    .header-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 20px; padding: 30px 40px; margin-bottom: 30px; 
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.15); position: relative; overflow: hidden;
        border: 1px solid rgba(200, 163, 90, 0.2); display: flex; justify-content: space-between;
        align-items: center; flex-wrap: wrap; gap: 20px;
    }
    .header-banner::after {
        content: '\f5df'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute;
        right: 5%; top: -30px; font-size: 160px; color: #C8A35A; opacity: 0.04; transform: rotate(-10deg); pointer-events: none;
    }
    .banner-content { position: relative; z-index: 2; }
    .banner-title { font-size: 26px; font-weight: 800; color: #ffffff; margin-bottom: 6px; letter-spacing: 0.5px; display: flex; align-items: center; gap: 12px; }
    .banner-title i { color: #C8A35A; }
    .banner-desc { color: #94a3b8; font-size: 14px; font-weight: 400; margin: 0; max-width: 500px; line-height: 1.5; }

    /* Tombol Aksi Header */
    .btn-add-folder, .btn-import-excel {
        color: #ffffff; border: none; padding: 10px 20px; border-radius: 10px;
        font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;
        transition: all 0.3s ease; position: relative; z-index: 2; cursor: pointer;
    }
    .btn-add-folder { background: linear-gradient(135deg, #C8A35A 0%, #ae8b49 100%); box-shadow: 0 8px 20px rgba(200, 163, 90, 0.3); }
    .btn-add-folder:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(200, 163, 90, 0.4); color: white; }
    .btn-import-excel { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: 1px solid rgba(200, 163, 90, 0.4); box-shadow: 0 8px 20px rgba(15, 23, 42, 0.3); }
    .btn-import-excel:hover { transform: translateY(-3px); border-color: #C8A35A; color: white; background: #0f172a; }

    /* 🌟 2. GRID FOLDER PREMIUM 🌟 */
    .folder-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }
    .folder-card { background: #ffffff; border-radius: 16px; padding: 22px; border: 1px solid #e2e8f0; box-shadow: 0 5px 20px rgba(0,0,0,0.03); position: relative; overflow: hidden; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); opacity: 0; animation: fadeUpCard 0.6s ease forwards; display: flex; flex-direction: column; }
    @keyframes fadeUpCard { from { opacity: 0; transform: translateY(30px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .folder-card:hover { transform: translateY(-6px); box-shadow: 0 15px 35px rgba(0,0,0,0.08); border-color: #C8A35A; }
    .folder-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #C8A35A, #fde68a); opacity: 0; transition: 0.3s; }
    .folder-card:hover::before { opacity: 1; }

    .fc-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
    .fc-icon { width: 50px; height: 50px; border-radius: 12px; background: #f8fafc; color: #C8A35A; font-size: 24px; display: flex; justify-content: center; align-items: center; border: 1px solid #f1f5f9; transition: 0.3s; }
    .folder-card:hover .fc-icon { background: #fffbeb; border-color: #fde68a; transform: scale(1.1); }
    .fc-qr { width: 55px; height: 55px; border-radius: 8px; border: 1px solid #e2e8f0; padding: 2px; background: white; transition: 0.3s; }
    .folder-card:hover .fc-qr { border-color: #C8A35A; box-shadow: 0 4px 10px rgba(200, 163, 90, 0.2); }

    .fc-body { margin-bottom: 15px; flex-grow: 1; }
    .fc-kode { font-size: 12px; font-weight: 700; color: #4f46e5; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; display: block; }
    .fc-title { font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; line-height: 1.3; }
    .fc-nama { font-size: 13px; color: #64748b; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px; }

    .fc-stats { display: flex; gap: 10px; padding-top: 12px; border-top: 1px dashed #e2e8f0; margin-bottom: 18px; }
    .stat-item { flex: 1; }
    .stat-label { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 2px; }
    .stat-value { font-size: 13px; color: #1e293b; font-weight: 700; display: flex; align-items: center; gap: 5px; }
    .stat-value i { color: #C8A35A; font-size: 11px; }

    .fc-footer { display: flex; gap: 8px; }
    .btn-open-folder { flex: 1; background: #f0f9ff; color: #0284c7; border: 1px solid #e0f2fe; padding: 8px; border-radius: 8px; font-weight: 700; font-size: 13px; text-align: center; text-decoration: none; transition: 0.3s; display: flex; justify-content: center; align-items: center; gap: 6px; }
    .btn-open-folder i { transition: transform 0.3s; }
    .folder-card:hover .btn-open-folder i { transform: translateX(4px); } 
    .btn-open-folder:hover { background: #0284c7; color: #ffffff; border-color: #0284c7; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2); }

    .btn-fc-action { width: 38px; height: 38px; border-radius: 8px; display: flex; justify-content: center; align-items: center; border: 1px solid transparent; cursor: pointer; transition: 0.3s; font-size: 13px; }
    .btn-fc-edit { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .btn-fc-edit:hover { background: #d97706; color: #ffffff; border-color: #d97706; transform: translateY(-2px); }
    .btn-fc-del { background: #fef2f2; color: #ef4444; border-color: #fee2e2; }
    .btn-fc-del:hover { background: #ef4444; color: #ffffff; border-color: #ef4444; transform: translateY(-2px); }

    /* 🌟 MODAL STYLING UMUM 🌟 */
    .ea-modal-content { border-radius: 14px; border: none; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.2); }
    .ea-modal-header { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 16px 24px; border-bottom: 3px solid #C8A35A; color: white; }
    .ea-modal-title { font-weight: 700; font-size: 16px; margin: 0; display: flex; align-items: center; gap: 8px; }
    
    .ea-input { border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px; font-size: 13px; background: #f8fafc; transition: 0.3s; color: #334155; }
    .ea-input:focus { border-color: #C8A35A; box-shadow: 0 0 0 3px rgba(200, 163, 90, 0.15); background: #fff; outline: none; }
    .ea-label { font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px; }

    .btn-submit-emas { background: #C8A35A; color: white; border-radius: 8px; padding: 8px 20px; border: none; font-weight: 700; font-size: 13px; margin: 0; display: inline-flex; align-items: center; gap: 6px; transition: 0.3s; }
    .btn-submit-emas:hover { background: #ae8b49; transform: translateY(-2px); color: white; }

   /* 🔥 MOCKUP EXCEL (DIPERBAIKI JADI 9 KOLOM) 🔥 */
    .excel-mockup { border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; background: #ffffff; font-family: 'Segoe UI', sans-serif; margin-bottom: 12px; }
    /* Grid diubah menjadi repeat 9 kali agar A sampai I sejajar */
    .excel-header { display: grid; grid-template-columns: 30px repeat(9, 1fr); background: #f1f5f9; border-bottom: 1px solid #cbd5e1; }
    .excel-baris { display: grid; grid-template-columns: 30px repeat(9, 1fr); background: #fff; }
    
    .excel-cell-h { padding: 6px 4px; text-align: center; border-right: 1px solid #cbd5e1; font-size: 11px; font-weight: 700; color: #64748b; }
    .excel-num { background: #f1f5f9; padding: 6px 4px; text-align: center; border-right: 1px solid #cbd5e1; font-size: 11px; color: #94a3b8; font-weight: 600; }
    .excel-cell-data { padding: 6px 8px; border-right: 1px solid #e2e8f0; font-size: 11px; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .excel-cell-data:last-child, .excel-cell-h:last-child { border-right: none; }
    .cell-req { color: #ef4444; }
    .cell-example { color: #64748b; font-style: italic; font-weight: 500; }

    /* 🌟 DRAG & DROP ZONE (UKURAN SEDANG) 🌟 */
    .dz-import { border: 2px dashed #cbd5e1; border-radius: 10px; background-color: #f8fafc; padding: 22px 15px; text-align: center; cursor: pointer; transition: 0.3s; position: relative; }
    .dz-import:hover, .dz-import.dragover { border-color: #C8A35A; background-color: #fffbeb; }
    .btn-browse { background: white; border: 1px solid #cbd5e1; color: #475569; padding: 6px 14px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block; transition: 0.3s; cursor: pointer; margin-top: 10px; }
    .dz-import:hover .btn-browse { border-color: #C8A35A; color: #C8A35A; box-shadow: 0 3px 8px rgba(200, 163, 90, 0.1); }

    /* ======================================================= */
    /* 🌟 DARK MODE STYLES                                     */
    /* ======================================================= */
    body.dark-mode .folder-card { background: #0f172a; border-color: #1e293b; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    body.dark-mode .fc-title { color: #ffffff; }
    body.dark-mode .fc-nama { color: #94a3b8; }
    body.dark-mode .fc-stats { border-top-color: #1e293b; }
    body.dark-mode .stat-value { color: #f1f5f9; }
    body.dark-mode .fc-icon { background: rgba(30, 41, 59, 0.5); border-color: #1e293b; }

    body.dark-mode .btn-open-folder { background: rgba(2, 132, 199, 0.1); color: #38bdf8; border-color: rgba(2, 132, 199, 0.2); }
    body.dark-mode .btn-open-folder:hover { background: #0284c7 !important; color: #ffffff !important; border-color: #0284c7 !important; }
    body.dark-mode .btn-fc-edit { background: rgba(217, 119, 6, 0.1); color: #fbbf24; border-color: rgba(217, 119, 6, 0.2); }
    body.dark-mode .btn-fc-edit:hover { background: #d97706 !important; color: #ffffff !important; border-color: #d97706 !important; }
    body.dark-mode .btn-fc-del { background: rgba(225, 29, 72, 0.1); color: #fb7185; border-color: rgba(225, 29, 72, 0.2); }
    body.dark-mode .btn-fc-del:hover { background: #e11d48 !important; color: #ffffff !important; border-color: #e11d48 !important; }

    body.dark-mode .modal-content { background: #0f172a !important; border: 1px solid #1e293b !important; }
    body.dark-mode .ea-label { color: #cbd5e1 !important; }
    body.dark-mode .text-dark { color: #f8fafc !important; }
    body.dark-mode .text-muted { color: #94a3b8 !important; }
    
    /* Perbaikan Warna Input & Label di Mode Gelap */
    body.dark-mode .ea-label { color: #f8fafc !important; }
    body.dark-mode .ea-input { 
        background-color: #1e293b !important; 
        border-color: #334155 !important; 
        color: #ffffff !important;
    }
    body.dark-mode .ea-input::placeholder { 
        color: #94a3b8 !important;
        opacity: 1 !important; 
    }
    body.dark-mode .ea-input:focus { 
        background-color: #0f172a !important; 
        border-color: #C8A35A !important; 
        color: #ffffff !important;
    }
    body.dark-mode .alert-info { background: rgba(2, 132, 199, 0.1) !important; border-color: rgba(2, 132, 199, 0.2) !important; color: #38bdf8 !important; }

    body.dark-mode .excel-mockup { border-color: #334155; background: #0f172a; }
    body.dark-mode .excel-header, body.dark-mode .excel-num { background: #1e293b; border-color: #334155; color: #cbd5e1; }
    body.dark-mode .excel-cell-h { border-right-color: #334155; color: #cbd5e1; }
    body.dark-mode .excel-baris { background: #0f172a; }
    body.dark-mode .excel-cell-data { border-right-color: #1e293b; color: #f8fafc; }
    body.dark-mode .cell-example { color: #94a3b8; }

    body.dark-mode .dz-import { background: #1e293b; border-color: #475569; }
    body.dark-mode .dz-import:hover, body.dark-mode .dz-import.dragover { background: #0f172a; border-color: #C8A35A; }
    body.dark-mode .btn-browse { background: #0f172a; border-color: #475569; color: #cbd5e1; }
    body.dark-mode .dz-import:hover .btn-browse { border-color: #C8A35A; color: #C8A35A; }
</style>
@endpush

@section('content')
<div class="custom-page container-fluid">
    @include('partials.alerts')

    {{-- 🌟 1. PREMIUM DARK BANNER 🌟 --}}
    <div class="header-banner">
        <div class="banner-content">
            <h1 class="banner-title"><i class="fa-solid fa-vault"></i> Gudang Klasifikasi Arsip</h1>
            <p class="banner-desc">Pusat penyimpanan digital terpadu. Pilih brankas kategori di bawah ini untuk mengelola dokumen fisik dan digital.</p>
        </div>
        
        @can('admin')
        <div class="d-flex gap-2 flex-wrap" style="position: relative; z-index: 2;">
            <button type="button" class="btn-import-excel" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                <i class="fa-solid fa-file-excel fs-5" style="color: #C8A35A;"></i> Import Excel
            </button>
            <button type="button" class="btn-add-folder" data-bs-toggle="modal" data-bs-target="#modalTambahFolder">
                <i class="fa-solid fa-folder-plus fs-5"></i> Klasifikasi Baru
            </button>
        </div>
        @endcan
    </div>

    {{-- 🌟 2. GRID KARTU FOLDER 🌟 --}}
    <div class="folder-grid">
        @forelse($folders as $index => $folder)
        <div class="folder-card" style="animation-delay: {{ $index * 0.08 }}s;">
            <div class="fc-header">
                <div class="fc-icon">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <img src="{{ $folder['qr_url'] }}" alt="QR" class="fc-qr" title="Scan untuk akses cepat">
            </div>

            <div class="fc-body">
                <span class="fc-kode"># {{ $folder['kode'] }}</span>
                <h3 class="fc-title">Folder Klasifikasi</h3>
                <p class="fc-nama">{{ $folder['nama'] ?? 'Kategori Umum' }}</p>
            </div>

            <div class="fc-stats">
                <div class="stat-item flex-fill">
                    <span class="stat-label">Total Berkas</span>
                    <span class="stat-value"><i class="fa-solid fa-file-lines"></i> {{ $folder['total'] }} Dokumen</span>
                </div>
                <div class="stat-item flex-fill">
                    <span class="stat-label">Terakhir Update</span>
                    <span class="stat-value"><i class="fa-solid fa-clock-rotate-left"></i> {{ $folder['update'] }}</span>
                </div>
            </div>

            <div class="fc-footer">
                <a href="{{ route('arsip.folder.isi', $folder['kode']) }}" class="btn-open-folder">
                    Buka Brankas <i class="fa-solid fa-arrow-right-long ms-1"></i>
                </a>
                
                @can('admin')
                <button type="button" class="btn-fc-action btn-fc-edit" data-bs-toggle="modal" data-bs-target="#modalEditFolder{{ $folder['id'] }}" title="Ubah Nama Folder">
                    <i class="fa-solid fa-pen-nib"></i>
                </button>

                <form id="form-delete-folder-{{ $folder['id'] }}" action="{{ route('folders.destroy', $folder['id']) }}" method="POST" style="margin: 0;">
                    @csrf @method('DELETE')
                    <button type="button" onclick="confirmDeleteFolder({{ $folder['id'] }}, '{{ $folder['kode'] }}')" class="btn-fc-action btn-fc-del" title="Hapus Permanen">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </form>
                @endcan
            </div>
        </div>

        {{-- MODAL EDIT FOLDER --}}
        @can('admin')
        <div class="modal fade" id="modalEditFolder{{ $folder['id'] }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content ea-modal-content">
                    <div class="modal-header ea-modal-header">
                        <h5 class="ea-modal-title"><i class="fa-solid fa-pen-to-square" style="color: #C8A35A;"></i> Ubah Klasifikasi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('folders.update', $folder['id']) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-3 text-start">
                                <label class="ea-label">Kode (KP)</label>
                                <input type="text" name="kode_folder" class="form-control ea-input" value="{{ $folder['kode'] }}" required>
                            </div>
                            <div class="mb-2 text-start">
                                <label class="ea-label">Nama Kategori</label>
                                <input type="text" name="nama_folder" class="form-control ea-input" value="{{ $folder['nama'] }}">
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                            <button type="button" class="btn btn-light btn-sm fw-bold px-3 py-2" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                            <button type="submit" class="btn-submit-emas"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan

        @empty
        <div class="col-12">
            <div class="text-center py-5" style="background: #ffffff; border-radius: 20px; border: 1px dashed #cbd5e1;">
                <div style="color: #cbd5e1; font-size: 60px; margin-bottom: 15px;"><i class="fa-solid fa-box-open"></i></div>
                <h5 class="fw-bold text-dark">Gudang Kosong</h5>
                <p class="text-muted small">Belum ada folder klasifikasi.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- 🌟 MODAL TAMBAH FOLDER BARU 🌟 --}}
@can('admin')
<div class="modal fade" id="modalTambahFolder" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ea-modal-content">
            <div class="modal-header ea-modal-header">
                <h5 class="ea-modal-title"><i class="fa-solid fa-folder-plus" style="color: #C8A35A;"></i> Tambah Klasifikasi Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('folders.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 d-flex align-items-center mb-3" style="border-radius: 8px; font-size: 13px;">
                        <i class="fa-solid fa-circle-info fs-5 me-2"></i> 
                        <div>Gunakan kode unik (contoh: <strong>KP.01</strong>).</div>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="ea-label">Kode (Wajib)</label>
                        <input type="text" name="kode_folder" class="form-control ea-input" placeholder="Misal: KP.01" required autofocus>
                    </div>
                    <div class="mb-2 text-start">
                        <label class="ea-label">Nama Kategori (Opsional)</label>
                        <input type="text" name="nama_folder" class="form-control ea-input" placeholder="Misal: Surat Keputusan">
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light btn-sm fw-bold px-3 py-2" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn-submit-emas"><i class="fa-solid fa-save"></i> Buat Folder</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 🌟 MODAL IMPORT EXCEL (DIPERBAIKI) 🌟 --}}
<div class="modal fade" id="modalImportExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content ea-modal-content">
            
            <div class="modal-header ea-modal-header">
                <h5 class="ea-modal-title"><i class="fa-solid fa-file-excel" style="color: #C8A35A;"></i> Import Data Excel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formImportExcel" action="{{ route('arsip.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-3 px-4">
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="ea-label mb-0" style="font-size: 12px;"><i class="fa-solid fa-table me-1 text-success"></i> Panduan Format Kolom</span>
                        <a href="{{ route('arsip.download_template') }}" class="btn btn-sm btn-outline-success fw-bold py-1 px-2" style="border-radius: 8px; font-size: 11px;">
                            <i class="fa-solid fa-download"></i> Unduh Template
                        </a>
                    </div>

                    {{-- Mockup Excel yang Sejajar --}}
                    <div class="excel-mockup shadow-sm">
                        <div class="excel-header">
                            <div class="excel-cell-h" style="width: 30px;"></div>
                            <div class="excel-cell-h">A</div>
                            <div class="excel-cell-h">B</div>
                            <div class="excel-cell-h">C</div>
                            <div class="excel-cell-h">D</div>
                            <div class="excel-cell-h">E</div>
                            <div class="excel-cell-h">F</div>
                            <div class="excel-cell-h" style="color: #d97706;">G</div>
                            <div class="excel-cell-h" style="color: #d97706;">H</div>
                            <div class="excel-cell-h" style="color: #d97706;">I</div>
                        </div>
                        {{-- Baris 1: Header --}}
                        <div class="excel-baris">
                            <div class="excel-num">1</div>
                            <div class="excel-cell-data">kode_arsip <span class="cell-req">*</span></div>
                            <div class="excel-cell-data">nama_berkas <span class="cell-req">*</span></div>
                            <div class="excel-cell-data">tahun_berkas <span class="cell-req">*</span></div>
                            <div class="excel-cell-data text-muted">jumlah_berkas</div>
                            <div class="excel-cell-data text-muted">warna_berkas</div>
                            <div class="excel-cell-data text-muted">deskripsi_berkas</div>
                            <div class="excel-cell-data text-warning">retensi_aktif</div>
                            <div class="excel-cell-data text-warning">retensi_inaktif</div>
                            <div class="excel-cell-data text-warning">nasib_akhir</div>
                        </div>
                        {{-- Baris 2: Contoh Data --}}
                        <div class="excel-baris">
                            <div class="excel-num">2</div>
                            <div class="excel-cell-data cell-example" style="color: #ef4444;">KP.05.01</div>
                            <div class="excel-cell-data cell-example">Surat Cuti</div>
                            <div class="excel-cell-data cell-example">2024</div>
                            <div class="excel-cell-data cell-example">1</div>
                            <div class="excel-cell-data cell-example">Biru</div>
                            <div class="excel-cell-data cell-example">Arsip Biasa</div>
                            <div class="excel-cell-data cell-example">1</div>
                            <div class="excel-cell-data cell-example">2</div>
                            <div class="excel-cell-data cell-example">Musnah</div>
                        </div>
                    </div>
                    
                    <p style="font-size: 11px; margin-bottom: 15px; color: #64748b;">
                        <i class="fa-solid fa-circle-exclamation text-danger me-1"></i> <strong>Catatan:</strong> Pastikan <code>kode_arsip</code> tidak mengandung spasi (Gunakan format seperti <strong>KP.05</strong>).
                    </p>

                    {{-- Drop Zone Sedang --}}
                    <div class="dz-import" id="dropZoneImport">
                        <input type="file" name="file" id="fileInputImport" class="d-none" accept=".xlsx, .xls, .csv">
                        
                        {{-- Tampilan Kosong --}}
                        <div id="dzEmptyImport">
                            <i class="fa-solid fa-cloud-arrow-up mb-2" style="font-size: 34px; color: #C8A35A;"></i>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Tarik & Letakkan File Excel Disini</h6>
                            <p class="text-muted mb-0" style="font-size: 11px;">Mendukung format .xlsx, .xls, .csv (Maks. 5MB)</p>
                            <span class="btn-browse"><i class="fa-solid fa-folder-open me-1"></i> Telusuri File</span>
                        </div>

                        {{-- Tampilan Preview --}}
                        <div id="dzPreviewImport" class="d-none">
                            <i class="fa-solid fa-file-excel mb-2" style="font-size: 34px; color: #10b981;"></i>
                            <h6 class="fw-bold mb-1 text-dark" id="fileNameDisplayImport" style="font-size: 14px;">data.xlsx</h6>
                            <p class="text-success mb-2" style="font-size: 11px;"><i class="fa-solid fa-circle-check"></i> File siap di-import</p>
                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-3 fw-bold" id="btnRemoveFileImport" style="border-radius: 8px; font-size: 11px;">
                                Ganti File
                            </button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-top-0 px-4 pb-3 pt-0">
                    <button type="button" class="btn btn-light btn-sm fw-bold px-3 py-2" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn-submit-emas">
                        Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDeleteFolder(id, kode) {
        Swal.fire({
            title: 'Hapus ' + kode + '?',
            text: "Folder beserta seluruh isinya akan dipindahkan ke Tong Sampah.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', 
            cancelButtonColor: '#475569',  
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'border border-light shadow-lg', title: 'fs-5 fw-bold' }
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('form-delete-folder-' + id).submit(); }
        });
    }

    const dropZoneImport = document.getElementById('dropZoneImport'),
          fileInputImport = document.getElementById('fileInputImport'),
          dzEmptyImport = document.getElementById('dzEmptyImport'),
          dzPreviewImport = document.getElementById('dzPreviewImport'),
          fileNameDisplayImport = document.getElementById('fileNameDisplayImport'),
          btnRemoveFileImport = document.getElementById('btnRemoveFileImport');

    if(dropZoneImport) {
        dropZoneImport.addEventListener('click', (e) => { 
            if(e.target !== btnRemoveFileImport) fileInputImport.click(); 
        });
        
        dropZoneImport.addEventListener('dragover', (e) => { 
            e.preventDefault(); 
            dropZoneImport.classList.add('dragover'); 
        });
        
        dropZoneImport.addEventListener('dragleave', () => dropZoneImport.classList.remove('dragover'));
        
        dropZoneImport.addEventListener('drop', (e) => {
            e.preventDefault(); 
            dropZoneImport.classList.remove('dragover');
            if (e.dataTransfer.files.length) { 
                fileInputImport.files = e.dataTransfer.files; 
                updateDisplay(e.dataTransfer.files[0].name); 
            }
        });
        
        fileInputImport.addEventListener('change', function() { 
            if (this.files.length) updateDisplay(this.files[0].name); 
        });
        
        btnRemoveFileImport.addEventListener('click', (e) => {
            e.stopPropagation(); 
            fileInputImport.value = ''; 
            dzPreviewImport.classList.add('d-none'); 
            dzEmptyImport.classList.remove('d-none');
        });

        function updateDisplay(fileName) {
            fileNameDisplayImport.textContent = fileName;
            dzEmptyImport.classList.add('d-none');
            dzPreviewImport.classList.remove('d-none');
        }
    }
</script>
@endpush