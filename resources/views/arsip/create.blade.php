@extends('layouts.app')

@section('title', 'Tambah Berkas - E-Arsip Digital')
@section('header_title', 'Input Arsip Manual')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    
    .custom-page { font-family: 'Poppins', sans-serif; color: #334155; padding-top: 10px; padding-bottom: 50px; }
    
    /* 🌟 HEADER TITLE 🌟 */
    .ea-header { margin-bottom: 35px; text-align: center; }
    .ea-title { font-size: 28px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0; display: flex; align-items: center; justify-content: center; gap: 12px; }
    .ea-title i { color: #C8A35A; }
    .ea-subtitle { color: #64748b; font-size: 14px; margin: 0; }
    .ea-subtitle strong { color: #d97706; font-weight: 700; padding: 2px 8px; background: rgba(200, 163, 90, 0.1); border-radius: 6px; }

    /* 🌟 KOTAK FORM (CARD) 🌟 */
    .ea-card { background: #fff; border-radius: 20px; padding: 40px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.04); border: 1px solid #e2e8f0; max-width: 900px; margin: 0 auto; transition: all 0.3s ease; }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }

    /* 🌟 INPUT FIELDS 🌟 */
    .ea-input-group { margin-bottom: 20px; }
    .ea-label { display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
    .ea-input { width: 100%; padding: 15px 20px; border-radius: 12px; border: 1px solid #cbd5e1; background: #f8fafc; font-family: 'Poppins', sans-serif; font-size: 14px; transition: all 0.3s ease; color: #1e293b; box-sizing: border-box; }
    .ea-input::placeholder { color: #94a3b8; }
    .ea-input:focus { outline: none; border-color: #C8A35A; background: #fff; box-shadow: 0 0 0 4px rgba(200, 163, 90, 0.15); }

    /* 🔥 CSS KHUSUS KOTAK JRA (Agar Bisa Dark Mode) 🔥 */
    .jra-box { padding: 15px; border-radius: 12px; border: 1px solid transparent; transition: 0.3s; }
    .jra-box-aktif { background: #f0fdf4; border-color: #bbf7d0; }
    .jra-box-inaktif { background: #fef2f2; border-color: #fecaca; }
    .jra-box-nasib { background: #eff6ff; border-color: #bfdbfe; grid-column: 1 / -1; }
    .jra-input { background: #ffffff; cursor: pointer; }

    /* 🌟 AREA UPLOAD KUSTOM (DRAG & DROP) 🌟 */
    .drag-drop-zone { border: 2px dashed #cbd5e1; border-radius: 16px; padding: 40px 20px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden; }
    .drag-drop-zone:hover, .drag-drop-zone.dragover { border-color: #C8A35A; background: rgba(200, 163, 90, 0.05); }
    .drag-drop-zone.has-file { border-color: #10b981; background: #f0fdf4; border-style: solid; cursor: default; }
    .dz-icon { font-size: 45px; color: #C8A35A; margin-bottom: 15px; transition: 0.3s; display: inline-block; }
    .drag-drop-zone:hover .dz-icon { transform: translateY(-5px); }
    .dz-title { font-weight: 700; color: #334155; font-size: 16px; margin-bottom: 5px; }
    .dz-desc { color: #64748b; font-size: 12px; margin: 0; }

    /* 🌟 TOMBOL AKSI (DIPERBAIKI) 🌟 */
    .ea-btn-group { display: flex; justify-content: flex-end; align-items: center; gap: 15px; margin-top: 35px; padding-top: 25px; border-top: 1px solid #f1f5f9; }
    
    .btn-save { background: linear-gradient(135deg, #C8A35A 0%, #ae8b49 100%); color: #fff; padding: 14px 30px; border-radius: 12px; font-weight: 600; font-size: 14px; border: none; box-shadow: 0 8px 20px rgba(200, 163, 90, 0.25); transition: all 0.3s ease; cursor: pointer; display: inline-flex; justify-content: center; align-items: center; gap: 8px; white-space: nowrap; }
    .btn-save:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(200, 163, 90, 0.35); }
    
    .btn-cancel { background: #f1f5f9; color: #475569; padding: 14px 30px; border-radius: 12px; font-weight: 600; font-size: 14px; text-decoration: none; border: 1px solid #e2e8f0; transition: all 0.3s ease; display: inline-flex; justify-content: center; align-items: center; gap: 8px; white-space: nowrap; }
    .btn-cancel:hover { background: #e2e8f0; color: #1e293b; border-color: #cbd5e1; }

    /* ======================================================= */
    /* 🌟 DARK MODE KHUSUS HALAMAN INPUT ARSIP 🌟              */
    /* ======================================================= */
    body.dark-mode .ea-title { color: #ffffff; }
    body.dark-mode .ea-card { background: #0f172a; border-color: #1e293b; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4); }
    
    body.dark-mode .ea-label { color: #cbd5e1; }
    body.dark-mode .ea-input { background: #1e293b; border-color: #334155; color: #f8fafc; }
    body.dark-mode .ea-input:focus { background: #0b1120; border-color: #C8A35A; }

    body.dark-mode .jra-box-aktif { background: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.2); }
    body.dark-mode .jra-box-inaktif { background: rgba(239, 68, 68, 0.05); border-color: rgba(239, 68, 68, 0.2); }
    body.dark-mode .jra-box-nasib { background: rgba(59, 130, 246, 0.05); border-color: rgba(59, 130, 246, 0.2); }
    body.dark-mode .jra-input { background: #1e293b; }
    
    body.dark-mode .jra-box-aktif .text-success { color: #34d399 !important; }
    body.dark-mode .jra-box-inaktif .text-danger { color: #fb7185 !important; }
    body.dark-mode .jra-box-nasib .text-primary { color: #60a5fa !important; }
    
    body.dark-mode .drag-drop-zone { background: #1e293b; border-color: #334155; }
    body.dark-mode .drag-drop-zone:hover, body.dark-mode .drag-drop-zone.dragover { background: #0f172a; border-color: #C8A35A; }
    body.dark-mode .drag-drop-zone.has-file { background: rgba(16, 185, 129, 0.1); border-color: #10b981; }
    body.dark-mode .dz-title { color: #cbd5e1; }
    body.dark-mode .dz-desc { color: #94a3b8; }
    
    body.dark-mode .ea-btn-group { border-top-color: #1e293b; }
    body.dark-mode .btn-cancel { background: #1e293b; border-color: #334155; color: #cbd5e1; }
    body.dark-mode .btn-cancel:hover { background: #0b1120; border-color: #475569; color: #ffffff; }

    /* ======================================================= */
    /* 🌟 RESPONSIVE UNTUK MOBILE (DIPERBAIKI) 🌟              */
    /* ======================================================= */
    @media (max-width: 768px) { 
        .form-grid { grid-template-columns: 1fr; } 
        .ea-card { padding: 25px 20px; }
        
        /* Mengubah layout tombol jadi atas-bawah di HP */
        .ea-btn-group {
            flex-direction: column-reverse; /* Batalkan di bawah, Simpan di atas */
            align-items: stretch; /* Tombol melebar penuh */
            gap: 12px;
        }
        .btn-save, .btn-cancel {
            width: 100%;
            padding: 15px;
        }
    }

    /* Animasi Masuk */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .anim-fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endpush

@section('content')
<div class="custom-page container anim-fade-up">
    
    @include('partials.alerts')

    <div class="ea-header">
        <h1 class="ea-title">
            <i class="fa-solid fa-folder-plus"></i> Input Berkas Manual
        </h1>
        <p class="ea-subtitle">Tambahkan data arsip baru ke dalam klasifikasi folder <strong>{{ $kode }}</strong></p>
    </div>

    <div class="ea-card">
        <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                {{-- BARIS 1: KODE KLASIFIKASI & NAMA BERKAS --}}
                <div class="ea-input-group">
                    <label class="ea-label">Kode Klasifikasi <span class="text-danger">*</span></label>
                    <input type="text" name="kode_arsip" class="ea-input fw-bold" value="{{ $kode }}" style="color: #C8A35A;" required>
                </div>

                <div class="ea-input-group">
                    <label class="ea-label">Nama Berkas <span class="text-danger">*</span></label>
                    <input type="text" name="nama_berkas" class="ea-input" placeholder="Contoh: Bezzeting Pegawai" required autofocus>
                </div>
                
                {{-- BARIS 2: TAHUN BERKAS & JUMLAH BERKAS --}}
                <div class="ea-input-group">
                    <label class="ea-label">Tahun Berkas <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_berkas" class="ea-input" placeholder="Misal: 2024 atau Periode Jan-Feb 2024" value="{{ date('Y') }}" required>
                </div>

                <div class="ea-input-group">
                    <label class="ea-label">Jumlah Berkas</label>
                    <input type="number" name="jumlah_berkas" class="ea-input" placeholder="Misal: 1" value="1" min="1">
                </div>

                {{-- 🔥 BARIS 3: KOTAK INPUT JRA (SEJAJAR SEMPURNA) 🔥 --}}
                <div class="ea-input-group jra-box jra-box-aktif">
                    <label class="ea-label text-success"><i class="fa-solid fa-clock-rotate-left"></i> Retensi Aktif (Tahun)</label>
                    <input type="number" name="retensi_aktif" class="ea-input jra-input" value="0" min="0">
                </div>

                <div class="ea-input-group jra-box jra-box-inaktif">
                    <label class="ea-label text-danger"><i class="fa-solid fa-box-archive"></i> Retensi Inaktif (Tahun)</label>
                    <input type="number" name="retensi_inaktif" class="ea-input jra-input" value="0" min="0">
                </div>

                {{-- BARIS 4: NASIB AKHIR DOKUMEN --}}
                <div class="ea-input-group jra-box jra-box-nasib">
                    <label class="ea-label text-primary"><i class="fa-solid fa-gavel"></i> Nasib Akhir Dokumen</label>
                    <select name="nasib_akhir" class="ea-input jra-input">
                        <option value="Musnah" selected>Musnah (Hancurkan setelah Inaktif)</option>
                        <option value="Permanen">Permanen (Simpan sebagai Sejarah)</option>
                    </select>
                </div>

                {{-- BARIS 5: LOKASI FISIK BERKAS --}}
                <div class="ea-input-group" style="grid-column: 1 / -1;">
                    <label class="ea-label"><i class="fa-solid fa-location-dot"></i> Lokasi Fisik Berkas</label>
                    <select name="status_lokasi" class="ea-input" style="cursor: pointer;">
                        <option value="Internal" selected>Internal (Masih di Ruangan/Unit Kerja)</option>
                        <option value="Bagian Umum">Diserahkan ke Bag. Umum</option>
                    </select>
                </div>

                {{-- BARIS 6: WARNA BERKAS --}}
                <div class="ea-input-group" style="grid-column: 1 / -1;">
                    <label class="ea-label">Warna Berkas</label>
                    <input type="text" name="warna_berkas" class="ea-input" placeholder="Misal: Biru">
                </div>

                {{-- BARIS 7: DESKRIPSI BERKAS --}}
                <div class="ea-input-group" style="grid-column: 1 / -1;">
                    <label class="ea-label">Deskripsi Berkas</label>
                    <input type="text" name="deskripsi_berkas" class="ea-input" placeholder="Penjelasan singkat mengenai isi atau tujuan berkas...">
                </div>
            </div>

            <div class="ea-input-group mt-4">
                <label class="ea-label" style="color: #C8A35A;"><i class="fa-solid fa-file-pdf me-1"></i> Upload File Fisik (Opsional)</label>
                
                {{-- 🌟 KOTAK DRAG & DROP PINTAR 🌟 --}}
                <div class="drag-drop-zone" id="dropZone">
                    <input type="file" name="file_dokumen" id="fileInput" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                    
                    {{-- Tampilan saat KOSONG --}}
                    <div id="dzEmpty">
                        <i class="fa-solid fa-cloud-arrow-up dz-icon"></i>
                        <h6 class="dz-title">Tarik & Lepas File ke Sini</h6>
                        <p class="dz-desc">Atau klik area ini untuk mencari file dari komputer (Format: PDF, JPG, PNG. Max: 5MB)</p>
                    </div>

                    {{-- Tampilan saat FILE TERPILIH --}}
                    <div id="dzPreview" class="d-none">
                        <i class="fa-solid fa-file-circle-check" style="font-size: 45px; color: #10b981; margin-bottom: 15px;"></i>
                        <h6 class="dz-title text-success" id="fileNameDisplay">nama_file.pdf</h6>
                        <p class="dz-desc mb-3" id="fileSizeDisplay">2.5 MB</p>
                        <button type="button" class="btn btn-sm" id="btnRemoveFile" style="background: #fee2e2; color: #ef4444; border-radius: 8px; font-weight: 600; padding: 6px 15px;">
                            <i class="fa-solid fa-trash-can me-1"></i> Batal / Ganti File
                        </button>
                    </div>
                </div>
            </div>

            <div class="ea-btn-group">
                <a href="{{ route('arsip.folder.isi', $kode) }}" class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i> Batalkan
                </a>
                <button type="submit" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Arsip
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const dzEmpty = document.getElementById('dzEmpty');
        const dzPreview = document.getElementById('dzPreview');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const fileSizeDisplay = document.getElementById('fileSizeDisplay');
        const btnRemove = document.getElementById('btnRemoveFile');

        // 1. Jika kotak diklik, buka jendela file komputer
        dropZone.addEventListener('click', (e) => {
            if (e.target.closest('#btnRemoveFile')) return;
            if (!dropZone.classList.contains('has-file')) {
                fileInput.click();
            }
        });

        // 2. Efek saat file diseret melayang di atas kotak
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault(); 
            dropZone.classList.add('dragover');
        });
        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        });

        // 3. Efek saat file dilepaskan (dijatuhkan) ke dalam kotak
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files; 
                updateUI(e.dataTransfer.files[0]); 
            }
        });

        // 4. Jika file dipilih lewat klik biasa (browse)
        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                updateUI(this.files[0]);
            }
        });

        // 5. Tombol untuk membatalkan file
        btnRemove.addEventListener('click', (e) => {
            e.stopPropagation(); 
            fileInput.value = ''; 
            dropZone.classList.remove('has-file');
            dzPreview.classList.add('d-none');
            dzEmpty.classList.remove('d-none');
        });

        // Fungsi memperbarui Tampilan (Nama & Ukuran)
        function updateUI(file) {
            let size = (file.size / 1024).toFixed(2);
            let sizeText = size + ' KB';
            if (size > 1024) { sizeText = (size / 1024).toFixed(2) + ' MB'; }

            fileNameDisplay.textContent = file.name;
            fileSizeDisplay.textContent = sizeText;
            
            dropZone.classList.add('has-file');
            dzEmpty.classList.add('d-none');
            dzPreview.classList.remove('d-none');
        }
    });
</script>
@endpush