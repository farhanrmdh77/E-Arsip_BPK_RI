@extends('layouts.app')

@section('title', 'Konfigurasi Sistem - ' . \App\Models\Setting::getAppName())
@section('header_title', 'Pengaturan Sistem')

@push('styles')
<style>
    .custom-page { font-family: 'Poppins', sans-serif; color: #334155; padding-top: 10px; padding-bottom: 50px; }
    
    /* 🌟 1. PREMIUM DARK BANNER 🌟 */
    .header-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 20px; padding: 35px 40px; margin-bottom: 30px; 
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.15);
        position: relative; overflow: hidden; border: 1px solid rgba(200, 163, 90, 0.2);
    }
    .header-banner::after {
        content: '\f3fe'; /* Ikon Sliders */
        font-family: 'Font Awesome 6 Free'; font-weight: 900;
        position: absolute; right: 5%; top: -20px; font-size: 160px;
        color: #C8A35A; opacity: 0.05; transform: rotate(-10deg); pointer-events: none;
    }
    .banner-title { font-size: 26px; font-weight: 800; color: #ffffff; margin-bottom: 8px; display: flex; align-items: center; gap: 12px; position: relative; z-index: 2; }
    .banner-title i { color: #C8A35A; }
    .banner-desc { color: #94a3b8; font-size: 14px; margin: 0; max-width: 600px; position: relative; z-index: 2; }

    /* 🌟 2. CONFIGURATION CARDS 🌟 */
    .config-card {
        background: #ffffff; border-radius: 20px; padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); border: 1px solid #e2e8f0;
        height: 100%; transition: all 0.3s ease; position: relative; overflow: hidden;
        opacity: 0; animation: fadeUpCard 0.6s ease forwards; display: flex; flex-direction: column;
    }
    .config-card:hover { border-color: #C8A35A; box-shadow: 0 15px 35px rgba(200, 163, 90, 0.08); transform: translateY(-5px); }
    @keyframes fadeUpCard { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .card-header-custom { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 20px; }
    .header-icon { 
        width: 45px; height: 45px; background: #f8fafc; border-radius: 12px; 
        display: flex; align-items: center; justify-content: center; font-size: 20px; color: #C8A35A; border: 1px solid #f1f5f9;
    }
    .header-title { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; }

    /* FORM STYLING UMUM */
    .form-label-custom { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: block; }
    .form-input-custom { 
        background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 14px 18px; 
        font-size: 14px; font-weight: 500; color: #1e293b; transition: 0.3s; width: 100%;
    }
    .form-input-custom:focus { outline: none; border-color: #C8A35A; background: #ffffff; box-shadow: 0 0 0 4px rgba(200, 163, 90, 0.1); }

    /* AREA PREVIEW & UPLOAD LOGO */
    .logo-upload-box { 
        display: flex; align-items: center; gap: 20px; background: #f8fafc; padding: 20px; 
        border-radius: 15px; border: 2px dashed #cbd5e1; transition: 0.3s ease;
    }
    .logo-upload-box:hover { border-color: #C8A35A; background: rgba(200, 163, 90, 0.02); }

    .logo-preview-wrapper { 
        width: 90px; height: 90px; background: #ffffff; border-radius: 15px; 
        display: flex; align-items: center; justify-content: center; overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; flex-shrink: 0;
        transition: 0.3s;
    }
    .logo-preview-img { width: 100%; height: 100%; object-fit: contain; padding: 8px; }
    .logo-placeholder { font-size: 30px; color: #cbd5e1; }

    /* Input File Custom */
    .file-input-custom { 
        display: block; width: 100%; font-size: 13px; color: #64748b; font-weight: 500;
        background: #ffffff; border: 1px solid #cbd5e1; border-radius: 10px; padding: 6px;
        transition: 0.3s; cursor: pointer;
    }
    .file-input-custom:hover { border-color: #C8A35A; }
    .file-input-custom::file-selector-button {
        background: #1e293b; color: #ffffff; border: none; padding: 8px 16px;
        border-radius: 6px; font-weight: 600; margin-right: 15px; cursor: pointer; transition: 0.3s;
    }
    .file-input-custom::file-selector-button:hover { background: #0f172a; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.2); transform: translateY(-2px); }

    /* TOMBOL SIMPAN KONFIGURASI */
    .btn-save-config {
        background: linear-gradient(135deg, #C8A35A 0%, #ae8b49 100%);
        color: #ffffff; border: none; padding: 14px 25px; border-radius: 10px; width: 100%;
        font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 10px;
        box-shadow: 0 8px 20px rgba(200, 163, 90, 0.3); transition: 0.3s; cursor: pointer; margin-top: auto;
    }
    .btn-save-config:hover:not(:disabled) { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(200, 163, 90, 0.4); color: white; }
    .btn-save-config:disabled { background: #cbd5e1; box-shadow: none; cursor: not-allowed; color: #64748b; border-color: #cbd5e1; }

    /* 🌟 KOTAK SERVER & DATABASE 🌟 */
    .server-box { 
        background: #0f172a; border-radius: 15px; padding: 20px 25px; color: white; 
        position: relative; overflow: hidden; border: 1px solid #1e293b;
    }
    .server-box::before { content: ''; position: absolute; top: 0; right: 0; width: 150px; height: 150px; background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 70%); pointer-events: none; transition: 0.5s;}
    
    /* CSS Status Online/Offline */
    .server-status { display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; width: fit-content; transition: 0.3s; }
    .server-status.online { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
    .server-status.online .dot { width: 8px; height: 8px; background: #10b981; border-radius: 50%; box-shadow: 0 0 10px #10b981; animation: pulseDot 2s infinite; }
    
    .server-status.offline { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }
    .server-status.offline .dot { width: 8px; height: 8px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 10px #ef4444; animation: pulseDotError 0.8s infinite; }

    .server-box.is-offline::before { background: radial-gradient(circle, rgba(239,68,68,0.15) 0%, transparent 70%); }

    @keyframes pulseDot { 0% { transform: scale(0.95); opacity: 0.5; } 50% { transform: scale(1.2); opacity: 1; } 100% { transform: scale(0.95); opacity: 0.5; } }
    @keyframes pulseDotError { 0% { transform: scale(0.8); opacity: 0.8; } 50% { transform: scale(1.4); opacity: 1; } 100% { transform: scale(0.8); opacity: 0.8; } }

    /* 🌟 TOMBOL BACKUP 🌟 */
    .btn-backup {
        background: #10b981; border: 2px solid #10b981; color: #ffffff; 
        padding: 14px 25px; border-radius: 10px; width: 100%; text-decoration: none;
        font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: all 0.3s ease; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); margin-top: auto;
    }
    .btn-backup:hover:not(.disabled) { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(16, 185, 129, 0.4); color: white; background: #059669; border-color: #059669; }
    .btn-backup.disabled { background: #475569; box-shadow: none; cursor: not-allowed; opacity: 0.6; pointer-events: none; border-color: #475569; }

    /* ======================================================= */
    /* 🌟 DARK MODE OVERRIDES 🌟                               */
    /* ======================================================= */
    body.dark-mode .config-card { background: #0f172a; border-color: #1e293b; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    body.dark-mode .config-card:hover { border-color: #C8A35A; }
    body.dark-mode .card-header-custom { border-bottom-color: #1e293b; }
    body.dark-mode .header-icon { background: rgba(30, 41, 59, 0.5); border-color: #1e293b; }
    body.dark-mode .header-title { color: #ffffff; }
    
    body.dark-mode .form-label-custom { color: #cbd5e1; }
    body.dark-mode .form-input-custom { background: #1e293b; border-color: #334155; color: #f8fafc; }
    body.dark-mode .form-input-custom:focus { border-color: #C8A35A; background: #0b1120; }

    body.dark-mode .logo-upload-box { background: #1e293b; border-color: #475569; }
    body.dark-mode .logo-upload-box:hover { background: #0b1120; border-color: #C8A35A; }
    body.dark-mode .logo-preview-wrapper { background: rgba(255,255,255,0.85); border-color: #334155; }
    
    body.dark-mode .file-input-custom { background: #0f172a; border-color: #334155; color: #cbd5e1; }
    body.dark-mode .file-input-custom::file-selector-button { background: rgba(200, 163, 90, 0.15); color: #fde68a; border: 1px solid rgba(200, 163, 90, 0.3); }
    body.dark-mode .file-input-custom::file-selector-button:hover { background: #C8A35A; color: #ffffff; }
    body.dark-mode .text-muted { color: #94a3b8 !important; }

    body.dark-mode .server-box { background: #1e293b; border-color: #334155; }
    body.dark-mode .server-status.online { color: #34d399; }
    body.dark-mode .server-status.offline { color: #fb7185; }
</style>
@endpush

@section('content')
<div class="custom-page container-fluid">
    @include('partials.alerts')

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px; border-left: 5px solid #ef4444 !important; background-color: #fef2f2; color: #991b1b;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- 🌟 1. PREMIUM HEADER 🌟 --}}
    <div class="header-banner">
        <h1 class="banner-title"><i class="fa-solid fa-sliders"></i> Konfigurasi Sistem</h1>
        <p class="banner-desc">Atur identitas aplikasi E-Arsip, perbarui logo instansi, dan kelola keamanan database pusat secara mandiri.</p>
    </div>

    {{-- 🌟 2. CONFIGURATION GRID 🌟 --}}
    <div class="row g-4">
        
        {{-- PANEL KIRI: IDENTITAS APLIKASI --}}
        <div class="col-lg-7">
            <div class="config-card" style="animation-delay: 0.1s;">
                <div class="card-header-custom">
                    <div class="header-icon"><i class="fa-solid fa-id-card-clip"></i></div>
                    <div>
                        <h3 class="header-title">Identitas Aplikasi</h3>
                        <span class="text-muted" style="font-size: 12px;">Personalisasi nama dan logo sistem.</span>
                    </div>
                </div>

                {{-- Wrapper Form (Flex-Grow 1 untuk menekan tombol ke paling bawah) --}}
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100" style="flex-grow: 1;">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label-custom">Nama Instansi / Aplikasi</label>
                        {{-- Set tinggi input 50px untuk presisi penyelarasan di kanan --}}
                        <input type="text" name="app_name" class="form-input-custom" value="{{ \App\Models\Setting::getAppName() }}" placeholder="Misal: E-Arsip BPK Jambi" required style="height: 50px;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Logo Instansi Resmi</label>
                        <div class="logo-upload-box">
                            <div class="logo-preview-wrapper">
                                @php $appLogo = \App\Models\Setting::getAppLogo(); @endphp
                                @if($appLogo)
                                    <img src="{{ $appLogo }}" alt="Logo App" id="preview-image" class="logo-preview-img">
                                @else
                                    <i class="fa-solid fa-image logo-placeholder" id="preview-icon"></i>
                                    <img src="" alt="Preview" id="preview-image" class="logo-preview-img" style="display: none;">
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <input type="file" name="app_logo" id="logo-input" class="file-input-custom" accept=".png, .jpg, .jpeg, .svg">
                                <div class="mt-2 text-muted fw-medium" style="font-size: 11px;">* Format disarankan: PNG Transparan (Maks. 2MB).</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Simpan ditaruh di paling bawah otomatis karena mt-auto --}}
                    <button type="submit" id="btnSaveConfig" class="btn-save-config mt-auto">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Konfigurasi
                    </button>
                </form>
            </div>
        </div>

        {{-- PANEL KANAN: PEMELIHARAAN DATA --}}
        <div class="col-lg-5">
            <div class="config-card" style="animation-delay: 0.2s;">
                <div class="card-header-custom">
                    <div class="header-icon"><i class="fa-solid fa-database"></i></div>
                    <div>
                        <h3 class="header-title">Pemeliharaan Data</h3>
                        <span class="text-muted" style="font-size: 12px;">Keamanan dan Pencadangan Server.</span>
                    </div>
                </div>

                {{-- Wrapper Container (Flex-Grow 1 untuk menyamakan tinggi kolom kanan dan kiri) --}}
                <div class="d-flex flex-column h-100" style="flex-grow: 1;">
                    
                    {{-- Status Koneksi disejajarkan dengan Nama Instansi --}}
                    <div class="mb-4">
                        <label class="form-label-custom">Status Koneksi</label>
                        {{-- Wrapper ini diberi tinggi 50px agar sejajar dengan tinggi input text di sebelah kiri --}}
                        <div style="height: 50px; display: flex; align-items: center;">
                            <div class="server-status online" id="networkBadge" style="margin: 0;">
                                <div class="dot"></div> <span id="networkText">Server Online & Stabil</span>
                            </div>
                        </div>
                    </div>

                    {{-- Info Database disejajarkan dengan Logo Instansi --}}
                    <div class="mb-4">
                        <label class="form-label-custom">Informasi Basis Data</label>
                        <div class="server-box" id="serverBox">
                            <h5 class="fw-bold mb-1" style="color: white; font-size: 16px;">Database MySQL</h5>
                            <p style="font-size: 12px; color: #94a3b8; margin-bottom: 20px; line-height: 1.5;" id="networkDesc">
                                Pencadangan rutin sangat disarankan untuk mencegah kehilangan data akibat kerusakan server atau kesalahan manusia.
                            </p>
                            
                            <div class="d-flex align-items-center gap-3 mb-2" style="font-size: 13px;">
                                <i class="fa-solid fa-hard-drive" style="color: #94a3b8; width: 16px; text-align: center;"></i> 
                                <span style="color: #cbd5e1;">Penyimpanan:</span> 
                                <strong style="color: white;">Lokal (Public Storage)</strong>
                            </div>
                            <div class="d-flex align-items-center gap-3" style="font-size: 13px;">
                                <i class="fa-solid fa-shield-halved" style="color: #94a3b8; width: 16px; text-align: center;"></i> 
                                <span style="color: #cbd5e1;">Enkripsi Sandi:</span> 
                                <strong style="color: white; text-transform: capitalize;">{{ config('hashing.driver') ?? 'Bcrypt' }} (Aktif)</strong>
                            </div>
                        </div>
                    </div>
                    
                    {{-- 🌟 TOMBOL BACKUP RAPI DI PALING BAWAH (SEJAJAR SIMPAN) 🌟 --}}
                    <a href="{{ route('settings.backup') }}" id="btnBackupDb" class="btn-backup mt-auto" onclick="return confirm('Proses ini akan mengunduh seluruh isi database Anda dalam format SQL. Lanjutkan?')">
                        <i class="fa-solid fa-cloud-arrow-down"></i> Unduh Backup Database
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // --- FITUR PREVIEW LOGO ---
    document.getElementById('logo-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('preview-image');
                const previewIcon = document.getElementById('preview-icon');
                
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                
                if(previewIcon) {
                    previewIcon.style.display = 'none';
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // --- 🔥 FITUR REAL-TIME NETWORK LISTENER 🔥 ---
    function checkNetworkStatus() {
        const badge = document.getElementById('networkBadge');
        const text = document.getElementById('networkText');
        const desc = document.getElementById('networkDesc');
        const box = document.getElementById('serverBox');
        
        const btnSave = document.getElementById('btnSaveConfig');
        const btnBackup = document.getElementById('btnBackupDb');

        if (navigator.onLine) {
            // STATUS: ONLINE
            badge.className = 'server-status online';
            text.innerHTML = 'Server Online & Stabil';
            desc.innerHTML = 'Pencadangan rutin sangat disarankan untuk mencegah kehilangan data akibat kerusakan server atau kesalahan manusia.';
            box.classList.remove('is-offline');
            
            // Aktifkan Tombol Kembali
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Konfigurasi';
            btnBackup.classList.remove('disabled');
            btnBackup.innerHTML = '<i class="fa-solid fa-cloud-arrow-down"></i> Unduh Backup Database';
        } else {
            // STATUS: OFFLINE
            badge.className = 'server-status offline';
            text.innerHTML = 'Koneksi Terputus (Offline)';
            desc.innerHTML = '<strong class="text-danger">Peringatan:</strong> Komputer Anda kehilangan koneksi internet/jaringan. Fungsi penyimpanan dan backup dinonaktifkan sementara.';
            box.classList.add('is-offline');

            // Matikan Tombol untuk cegah error submit
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fa-solid fa-wifi"></i> Menunggu Koneksi...';
            btnBackup.classList.add('disabled');
            btnBackup.innerHTML = '<i class="fa-solid fa-ban"></i> Backup Dinonaktifkan';
        }
    }

    // Pasang alat pendengar (listener) ke browser
    window.addEventListener('online', checkNetworkStatus);
    window.addEventListener('offline', checkNetworkStatus);

    // Jalankan pengecekan pertama kali saat halaman dimuat
    document.addEventListener('DOMContentLoaded', checkNetworkStatus);
</script>
@endpush