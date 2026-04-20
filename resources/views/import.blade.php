@extends('layouts.app')

@section('title', 'Import Data Excel - E-Arsip BPK RI')

@push('styles')
<style>
    .ea-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 25px; 
        flex-wrap: wrap; 
        gap: 15px; 
    }
    .ea-title { 
        font-size: 26px; 
        font-weight: 700; 
        color: #0f172a; 
        margin: 0; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
    }
    
    /* TOMBOL KEMBALI (Sesuai Referensi Gambarmu) */
    .btn-kembali { 
        background: #f8fafc; 
        border: 1px solid #e2e8f0; 
        color: #475569; 
        padding: 10px 20px; 
        border-radius: 8px; 
        font-weight: 600; 
        font-size: 14px; 
        text-decoration: none; 
        transition: 0.3s; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
    }
    .btn-kembali:hover { 
        border-color: #cbd5e1; 
        background: #e2e8f0; 
        color: #1e293b; 
    }

    /* Styling Kotak Form Import */
    .ea-card { 
        background: #ffffff; 
        border-radius: 12px; 
        padding: 40px; 
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03); 
        border: 1px solid #e2e8f0; 
        max-width: 800px; 
        margin: 0 auto; 
    }
    
    .upload-area { 
        border: 2px dashed #cbd5e1; 
        border-radius: 12px; 
        padding: 50px 20px; 
        text-align: center; 
        background: #f8fafc; 
        transition: all 0.3s ease; 
        position: relative; 
        margin-bottom: 25px; 
    }
    .upload-area:hover { 
        border-color: #C8A35A; 
        background: #fffbeb; 
    }
    /* Memastikan area bisa diklik untuk upload */
    .upload-area input[type="file"] { 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        opacity: 0; 
        cursor: pointer; 
        z-index: 10; 
    }
    .upload-icon { 
        font-size: 50px; 
        color: #10b981; /* Warna hijau khas Excel */
        margin-bottom: 15px; 
    }
    
    /* Tombol Mulai Import */
    .btn-submit { 
        background: #C8A35A; 
        color: white; 
        border: none; 
        padding: 14px 30px; 
        border-radius: 8px; 
        font-weight: 700; 
        font-size: 15px; 
        width: 100%; 
        transition: 0.3s; 
        text-transform: uppercase; 
        box-shadow: 0 4px 10px rgba(200, 163, 90, 0.2); 
    }
    .btn-submit:hover { 
        background: #ae8b49; 
        transform: translateY(-2px); 
        box-shadow: 0 8px 20px rgba(200, 163, 90, 0.4); 
    }
    
    .alert-info-custom { 
        background: #f0f9ff; 
        border-left: 4px solid #0ea5e9; 
        padding: 15px 20px; 
        border-radius: 8px; 
        color: #0369a1; 
        font-size: 13px; 
        margin-bottom: 25px; 
    }
</style>
@endpush

@section('content')
<div class="container pt-2 pb-5">
    
    {{-- AREA HEADER & TOMBOL KEMBALI --}}
    <div class="ea-header mx-auto" style="max-width: 800px;">
        <h1 class="ea-title">
            <i class="fa-solid fa-file-import" style="color: #C8A35A;"></i> Import Data Excel
        </h1>
        
        {{-- TOMBOL KEMBALI YANG BARU DITAMBAHKAN --}}
        <a href="{{ route('arsip.folders') }}" class="btn-kembali">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- KOTAK KONTEN IMPORT --}}
    <div class="ea-card">
        @if(session('error'))
            <div class="alert alert-danger" style="border-radius: 8px; font-size: 14px;">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('error') }}
            </div>
        @endif

        <div class="alert-info-custom">
            <i class="fa-solid fa-circle-info me-2"></i> <strong>Informasi Sistem:</strong> Pastikan file Excel yang diunggah (.xlsx, .xls) memiliki format kolom baris pertama (Header) yang sesuai dengan struktur database arsip.
        </div>

        <form action="{{ route('arsip.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="upload-area">
                <i class="fa-solid fa-file-excel upload-icon"></i>
                <h5 class="fw-bold mb-2" style="color: #0f172a;">Pilih atau Tarik File Excel ke Sini</h5>
                <p class="text-muted small mb-0">Sistem mendukung format .xlsx, .xls, .csv (Maksimal ukuran file 10MB)</p>
                <input type="file" name="file" accept=".xlsx, .xls, .csv" required>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-cloud-arrow-up me-2"></i> Mulai Proses Import
            </button>
        </form>
    </div>
</div>
@endsection