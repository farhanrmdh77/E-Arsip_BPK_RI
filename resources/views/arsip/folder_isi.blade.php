@extends('layouts.app')

@section('title', 'Isi Folder ' . $kode . ' - ' . \App\Models\Setting::getAppName())
@section('header_title', 'Data Arsip Kategori: ' . $kode) 

@push('styles')
<style>
    .custom-page { font-family: 'Poppins', sans-serif; color: #334155; padding-top: 10px; padding-bottom: 50px; }
    
    /* 🌟 1. PREMIUM DARK BANNER 🌟 */
    .header-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 20px; padding: 35px 40px; margin-bottom: 25px; 
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.15); position: relative; overflow: hidden; border: 1px solid rgba(200, 163, 90, 0.2);
    }
    .header-banner::after {
        content: '\f07b'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; right: -20px; top: -40px; font-size: 200px; color: #C8A35A; opacity: 0.05; transform: rotate(-15deg); pointer-events: none;
    }
    .folder-info-wrapper { display: flex; align-items: center; gap: 20px; margin-bottom: 12px; position: relative; z-index: 2; }
    .folder-icon-box { 
        width: 60px; height: 60px; background: linear-gradient(135deg, #C8A35A 0%, #ae8b49 100%); 
        color: #ffffff; border-radius: 16px; display: flex; justify-content: center; align-items: center; font-size: 28px; box-shadow: 0 10px 20px rgba(200, 163, 90, 0.3);
    }
    .folder-title { font-size: 28px; font-weight: 800; color: #ffffff; margin: 0; letter-spacing: 0.5px; }
    .kode-badge { background: rgba(255, 255, 255, 0.1); color: #C8A35A; padding: 6px 16px; border-radius: 50px; font-size: 15px; font-weight: 700; border: 1px solid rgba(200, 163, 90, 0.4); backdrop-filter: blur(5px); }
    .folder-desc { color: #94a3b8; font-size: 14px; font-weight: 400; margin-left: 80px; margin-bottom: 0; position: relative; z-index: 2; }

    /* 🌟 2. SMART ACTION BAR 🌟 */
    .smart-action-bar {
        position: -webkit-sticky; position: sticky; top: 90px; z-index: 900;
        background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(16px) saturate(180%);
        padding: 18px 25px; border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(200, 163, 90, 0.1); 
        margin-bottom: 25px; display: flex; flex-direction: column; gap: 15px; 
        transition: top 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease; opacity: 1;
    }
    .smart-action-bar.hide-up { top: -150px; opacity: 0; pointer-events: none; }
    .smart-action-bar.peek-down { top: 90px; opacity: 1; pointer-events: auto; }

    .action-buttons { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
    .btn-action { padding: 10px 20px; border-radius: 40px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 8px; border: 1px solid transparent; transition: all 0.3s ease; text-decoration: none; cursor: pointer; }
    .btn-pdf { background: #fff5f5; color: #e11d48; border-color: #fecdd3; }
    .btn-pdf:hover { background: #e11d48; color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(225, 29, 72, 0.2); }
    .btn-add { background: #f0fdf4; color: #059669; border-color: #a7f3d0; }
    .btn-add:hover { background: #059669; color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(5, 150, 105, 0.2); }
    .btn-back { background: #f8fafc; color: #475569; border-color: #e2e8f0; }
    .btn-back:hover { background: #1e293b; color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(30, 41, 59, 0.2); }

    .bottom-bar-actions { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; width: 100%; }
    
    .entries-capsule { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 50px; padding: 4px 15px; display: inline-flex; align-items: center; gap: 8px; width: fit-content; transition: 0.3s; white-space: nowrap; }
    .entries-select { color: #1e293b; box-shadow: none !important; cursor: pointer; padding: 2px 25px 2px 5px; font-weight: 700; font-size: 13px; border: none; background-color: transparent; width: auto; }
    .entries-select:focus { outline: none; }
    /* 🔥 PERBAIKAN: Diperkecil dari 230px agar 4 kotak filter muat sebaris 🔥 */
    .filter-select { min-width: 140px; } 

    .btn-mode-hapus { background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2; padding: 8px 20px; border-radius: 50px; font-weight: 600; font-size: 13px; transition: 0.3s; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; }
    .btn-mode-hapus:hover { background: #ef4444; color: white; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2); transform: translateY(-2px); }
    .btn-mode-hapus.active { background: #1e293b; color: white; border-color: #1e293b; }

    .btn-bulk-delete { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: white; border: 1px solid transparent; padding: 8px 20px; border-radius: 50px; font-weight: 600; font-size: 13px; display: none; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 5px 15px rgba(225, 29, 72, 0.3); transition: 0.3s; white-space: nowrap; }
    .btn-bulk-delete:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(225, 29, 72, 0.4); }
    .btn-bulk-delete.show-anim { display: inline-flex; animation: popIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes popIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }

    .search-area { position: relative; width: 320px; margin: 0; }
    .search-input { width: 100%; padding: 12px 90px 12px 45px; border-radius: 50px; border: 1px solid #e2e8f0; font-size: 13px; color: #334155; background: #ffffff; transition: 0.3s; }
    .search-input:focus { border-color: #C8A35A; outline: none; background: #ffffff; box-shadow: 0 0 0 3px rgba(200, 163, 90, 0.15); }
    .search-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #C8A35A; font-size: 15px;}
    .btn-search { position: absolute; right: 5px; top: 5px; bottom: 5px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; border: none; border-radius: 40px; padding: 0 20px; font-weight: 600; font-size: 12px; transition: 0.3s; }
    .btn-search:hover { background: #C8A35A; box-shadow: 0 3px 10px rgba(200, 163, 90, 0.3); }

    /* 🌟 3. TABEL DATA PREMIUM 🌟 */
    .table-card { background: #ffffff; border-radius: 20px; padding: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); border: 1px solid #e2e8f0; overflow-x: auto; }
    .ea-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
    .ea-table th { padding: 15px 20px; color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; border-bottom: none; text-align: left; white-space: nowrap; }
    .cb-custom { width: 18px; height: 18px; cursor: pointer; accent-color: #ef4444; border: 2px solid #cbd5e1; border-radius: 4px; transition: 0.2s; margin-top: 3px;}
    
    .col-checkbox { width: 35px; text-align: center !important; padding-right: 5px !important; transition: 0.3s; }
    .col-no { width: 45px; text-align: center !important; } 
    .col-info { width: auto; } 
    .col-aksi { width: 1%; white-space: nowrap; text-align: center !important; padding-right: 15px !important; padding-left: 15px !important; } 

    .ea-table tbody tr { background-color: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.01); border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s; opacity: 0; animation: fadeUpRow 0.5s ease forwards; }
    @keyframes fadeUpRow { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .ea-table tbody tr:hover { transform: translateY(-2px) scale(1.005); box-shadow: 0 10px 25px rgba(0,0,0,0.04); z-index: 2; position: relative; }
    .ea-table tbody tr.row-selected { background-color: #fef2f2 !important; border-left: 3px solid #ef4444; box-shadow: 0 5px 15px rgba(239, 68, 68, 0.1); }
    
    .ea-table td { padding: 15px 20px; font-size: 13px; color: #334155; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    .ea-table td:first-child { border-left: 1px solid #f1f5f9; border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
    .ea-table td:last-child { border-right: 1px solid #f1f5f9; border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
    
    .doc-title { font-weight: 700; color: #0f172a; font-size: 14px; margin-bottom: 4px; }
    .doc-desc { color: #64748b; font-size: 12px; line-height: 1.5; max-width: 100%; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    /* 🔥 BADGE JRA DINAMIS 🔥 */
    .ea-badge-aktif { background: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; margin-top: 5px; }
    .ea-badge-inaktif { background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; margin-top: 5px; }
    .ea-badge-permanen { background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; margin-top: 5px; }

    /* 🔥 BADGE LOKASI FISIK 🔥 */
    .badge-lokasi-umum { background: #eff6ff; color: #0284c7; border: 1px solid #bae6fd; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; margin-top: 4px; }
    .badge-lokasi-internal { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; margin-top: 4px; }

    .badge-kp { background: #f8fafc; color: #0f172a; padding: 6px 10px; border-radius: 8px; font-weight: 700; font-size: 11px; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 4px; }
    .badge-kp i { color: #C8A35A; }
    .status-file-ada { display: inline-flex; align-items: center; gap: 5px; background: #f0fdf4; color: #16a34a; padding: 6px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none; transition: 0.2s; border: 1px solid #bbf7d0; }
    .status-file-ada:hover { background: #dcfce7; }
    .status-file-kosong { display: inline-flex; align-items: center; gap: 5px; background: #f8fafc; color: #64748b; padding: 6px 10px; border-radius: 8px; font-size: 11px; font-weight: 600; border: 1px dashed #cbd5e1; }
    
    .action-group-table { display: flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap; }
    .btn-t-view { background: #f0f9ff; color: #0284c7; padding: 8px 12px; border-radius: 8px; font-weight: 600; font-size: 12px; text-decoration: none; transition: all 0.3s ease; display: inline-flex; justify-content: center; align-items: center; gap: 5px; border: 1px solid #e0f2fe; }
    .btn-t-view:hover { background: #0284c7; color: #ffffff; border-color: #0284c7; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2); }
    .btn-t-edit { background: #fffbeb; color: #d97706; padding: 8px 12px; border-radius: 8px; font-weight: 600; font-size: 12px; text-decoration: none; transition: all 0.3s ease; display: inline-flex; justify-content: center; align-items: center; border: 1px solid #fde68a; }
    .btn-t-edit:hover { background: #d97706; color: #ffffff; border-color: #d97706; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(217, 119, 6, 0.2); }
    .btn-t-del { background: #fef2f2; color: #ef4444; padding: 8px 12px; border-radius: 8px; font-weight: 600; font-size: 12px; transition: all 0.3s ease; border: 1px solid #fee2e2; cursor: pointer; display: inline-flex; justify-content: center; align-items: center; margin: 0; }
    .btn-t-del:hover { background: #ef4444; color: #ffffff; border-color: #ef4444; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2); }

    /* CSS MODAL DETAIL COMPACT */
    .detail-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #cbd5e1; }
    .detail-item:last-child { border-bottom: none; }
    .detail-label { font-size: 12px; color: #64748b; font-weight: 600; }
    .detail-value { font-size: 13px; color: #1e293b; text-align: right; }
    .desc-scroll { max-height: 100px; overflow-y: auto; padding-right: 5px; margin-top: 5px; }
    .desc-scroll::-webkit-scrollbar { width: 4px; }
    .desc-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    /* KOTAK LAMPIRAN MODAL */
    .file-box-ada { background: #f0fdf4; border-color: #bbf7d0; }
    .file-box-ada .file-box-title { color: #16a34a; }
    .file-box-kosong { background: #f8fafc; border-color: #e2e8f0; }
    .file-box-kosong .file-box-title { color: #64748b; }

    /* 🔥 PERBAIKAN: CSS PAGINATION & INFO TEXT (MEMAKSA ANGKA MUNCUL DI MOBILE) 🔥 */
    .pagination-wrapper { margin-top: 20px; padding-top: 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
    .page-count-text { color: #0f172a; font-weight: 700; }

    /* Mematikan fungsi bawaan Laravel yang mengubah tampilan jadi Previous/Next di layar HP */
    .pagination-links nav > div.d-sm-none { display: none !important; } 
    /* Memaksa list angka pagination tampil di semua ukuran layar */
    .pagination-links nav > div.d-none.d-sm-flex { display: flex !important; justify-content: center !important; width: 100%; }

    .pagination-links nav > div.d-sm-flex > div:first-child { display: none !important; }
    .pagination-links nav > div.d-sm-flex > div:last-child { margin-left: auto; display: flex; justify-content: flex-end; width: 100%; }
    .pagination-links nav { margin: 0; width: 100%; }
    .pagination { margin-bottom: 0; gap: 4px; flex-wrap: wrap; }
    .pagination .page-item { margin: 0; }
    .pagination .page-link { color: #475569; border-color: transparent; border-radius: 8px; font-weight: 600; font-size: 13px; transition: all 0.3s ease; background-color: #f8fafc; padding: 8px 14px;}
    .pagination .page-link:hover { background-color: rgba(200, 163, 90, 0.1); color: #C8A35A; transform: translateY(-2px); }
    .pagination .page-item.active .page-link { background-color: #C8A35A; border-color: #C8A35A; color: white; box-shadow: 0 4px 10px rgba(200, 163, 90, 0.3); transform: translateY(-2px); }
    
    /* ======================================================= */
    /* 📱 RESPONSIVE MOBILE FIXES 📱                           */
    /* ======================================================= */
    @media (max-width: 768px) {
        .header-banner { padding: 25px 20px; text-align: center; }
        .folder-info-wrapper { flex-direction: column; justify-content: center; gap: 10px; margin-bottom: 15px; }
        .folder-desc { margin-left: 0; text-align: center; font-size: 13px; }
        .folder-icon-box { margin: 0 auto; width: 50px; height: 50px; font-size: 24px; }
        .folder-title { font-size: 22px; }
        .kode-badge { font-size: 13px; }

        .smart-action-bar { padding: 15px; position: relative; top: 0; } 
        .smart-action-bar > .d-flex { flex-direction: column; align-items: stretch !important; gap: 12px !important; }
        .action-buttons { width: 100%; display: flex; flex-direction: column; gap: 8px; }
        .action-buttons .btn-action { width: 100%; justify-content: center; }
        .search-area { width: 100%; margin: 0; }
        
        .bottom-bar-actions { flex-direction: column; align-items: stretch; gap: 10px; }
        .bottom-bar-actions > .d-flex { flex-direction: column; width: 100%; align-items: stretch !important; gap: 10px !important; }
        .entries-capsule { width: 100%; justify-content: space-between; padding: 8px 15px; border-radius: 12px; } 
        .entries-capsule > span { font-size: 11px !important; }
        .filter-select, .entries-select { min-width: 0; width: auto; max-width: 60%; font-size: 12px; text-align: right; }
        
        .ms-auto { margin-left: 0 !important; }
        .btn-mode-hapus, .btn-bulk-delete { width: 100%; justify-content: center; margin-top: 5px; }

        /* Memposisikan Pagination Angka agar ke tengah di Layar HP */
        .pagination-wrapper { flex-direction: column; align-items: center; text-align: center; gap: 15px; }
        .pagination-links nav > div.d-sm-flex > div:last-child { justify-content: center !important; margin-left: 0; }
    }

    /* ======================================================= */
    /* 🌟 DARK MODE SUPPORT 🌟 */
    /* ======================================================= */
    body.dark-mode .smart-action-bar { background: rgba(15, 23, 42, 0.85); border-color: #334155; }
    body.dark-mode .entries-capsule { background: #1e293b; border-color: #334155; }
    body.dark-mode .entries-capsule span { color: #94a3b8 !important; }
    body.dark-mode .entries-select { color: #cbd5e1; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23cbd5e1' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); }
    body.dark-mode .entries-select option { background: #0f172a; color: #cbd5e1; }
    body.dark-mode .search-input { background: #1e293b; border-color: #334155; color: #f8fafc; }
    body.dark-mode .search-input:focus { background: #0b1120; border-color: #C8A35A; }
    body.dark-mode .btn-mode-hapus { background: rgba(239, 68, 68, 0.15); color: #fb7185; border-color: rgba(239, 68, 68, 0.3); }
    body.dark-mode .btn-mode-hapus:hover { background: #ef4444; color: white; }
    body.dark-mode .btn-mode-hapus.active { background: #C8A35A; color: white; border-color: #C8A35A; }
    body.dark-mode .ea-table tbody tr.row-selected { background-color: rgba(239, 68, 68, 0.1) !important; border-left-color: #ef4444; }

    body.dark-mode .pagination-wrapper { border-color: #1e293b; }
    body.dark-mode .page-count-text { color: #ffffff !important; } 

    body.dark-mode .pagination .page-link { background-color: #1e293b; color: #cbd5e1; }
    body.dark-mode .pagination .page-link:hover { background-color: #0f172a; color: #C8A35A; }
    body.dark-mode .pagination .page-item.active .page-link { background-color: #C8A35A; color: white; }
    body.dark-mode .ea-badge-aktif { background: rgba(16, 185, 129, 0.1) !important; color: #34d399 !important; border-color: rgba(16, 185, 129, 0.2) !important; }
    body.dark-mode .ea-badge-inaktif { background: rgba(239, 68, 68, 0.1) !important; color: #fb7185 !important; border-color: rgba(239, 68, 68, 0.2) !important; }
    body.dark-mode .ea-badge-permanen { background: rgba(59, 130, 246, 0.1) !important; color: #60a5fa !important; border-color: rgba(59, 130, 246, 0.2) !important; }

    /* 🔥 DARK MODE BADGE LOKASI FISIK 🔥 */
    body.dark-mode .badge-lokasi-umum { background: rgba(2, 132, 199, 0.1) !important; color: #38bdf8 !important; border-color: rgba(2, 132, 199, 0.2) !important; }
    body.dark-mode .badge-lokasi-internal { background: rgba(71, 85, 105, 0.2) !important; color: #94a3b8 !important; border-color: rgba(71, 85, 105, 0.3) !important; }

    body.dark-mode .badge-kp { background: rgba(200, 163, 90, 0.15); color: #fde68a; border-color: rgba(200, 163, 90, 0.3); }
    body.dark-mode .badge-kp i { color: #fde68a; }
    body.dark-mode .status-file-ada { background: rgba(16, 185, 129, 0.1); color: #34d399; border-color: rgba(16, 185, 129, 0.2); }
    body.dark-mode .status-file-ada:hover { background: rgba(16, 185, 129, 0.2); }
    body.dark-mode .status-file-kosong { background: rgba(71, 85, 105, 0.2); color: #94a3b8; border-color: #334155; }
    
    body.dark-mode .btn-pdf { background: rgba(225, 29, 72, 0.1) !important; color: #fb7185 !important; border-color: rgba(225, 29, 72, 0.2) !important; }
    body.dark-mode .btn-pdf:hover { background: #e11d48 !important; color: #ffffff !important; }
    body.dark-mode .btn-add { background: rgba(16, 185, 129, 0.1) !important; color: #34d399 !important; border-color: rgba(16, 185, 129, 0.2) !important; }
    body.dark-mode .btn-add:hover { background: #059669 !important; color: #ffffff !important; }
    body.dark-mode .btn-back { background: rgba(71, 85, 105, 0.2) !important; color: #cbd5e1 !important; border-color: rgba(71, 85, 105, 0.3) !important; }
    body.dark-mode .btn-back:hover { background: #475569 !important; color: #ffffff !important; }
    body.dark-mode .btn-bulk-delete { background: rgba(225, 29, 72, 0.1) !important; color: #fb7185 !important; border-color: rgba(225, 29, 72, 0.2) !important; }
    body.dark-mode .btn-bulk-delete:hover { background: #e11d48 !important; color: #ffffff !important; }

    /* Tombol Tabel Action Dark Mode */
    body.dark-mode .btn-t-view { background: rgba(2, 132, 199, 0.1) !important; color: #38bdf8 !important; border-color: rgba(2, 132, 199, 0.2) !important; }
    body.dark-mode .btn-t-view:hover { background: #0284c7 !important; color: #ffffff !important; border-color: #0284c7 !important; box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2) !important; }
    body.dark-mode .btn-t-edit { background: rgba(217, 119, 6, 0.1) !important; color: #fbbf24 !important; border-color: rgba(217, 119, 6, 0.2) !important; }
    body.dark-mode .btn-t-edit:hover { background: #d97706 !important; color: #ffffff !important; border-color: #d97706 !important; box-shadow: 0 4px 10px rgba(217, 119, 6, 0.2) !important; }
    body.dark-mode .btn-t-del { background: rgba(225, 29, 72, 0.1) !important; color: #fb7185 !important; border-color: rgba(225, 29, 72, 0.2) !important; }
    body.dark-mode .btn-t-del:hover { background: #e11d48 !important; color: #ffffff !important; border-color: #e11d48 !important; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2) !important; }

    /* Dark Mode Detail Modal Split */
    body.dark-mode .modal-content { background: #0f172a !important; border-color: #1e293b !important; }
    body.dark-mode .detail-item { border-bottom-color: #334155; }
    body.dark-mode .detail-label { color: #94a3b8; }
    body.dark-mode .detail-value { color: #f8fafc; }
    body.dark-mode .text-dark { color: #ffffff !important; }
    body.dark-mode .text-muted { color: #94a3b8 !important; }
    body.dark-mode .desc-scroll::-webkit-scrollbar-thumb { background: #475569; }

    /* KOTAK LAMPIRAN MODAL DARK MODE */
    body.dark-mode .file-box-ada { background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); }
    body.dark-mode .file-box-ada .file-box-title { color: #34d399; }
    body.dark-mode .file-box-kosong { background: rgba(71, 85, 105, 0.2); border-color: #334155; }
    body.dark-mode .file-box-kosong .file-box-title { color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="custom-page container-fluid">
    @include('partials.alerts')

    {{-- 🌟 1. PREMIUM DARK BANNER 🌟 --}}
    <div class="header-banner">
        <div class="folder-info-wrapper">
            <div class="folder-icon-box">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <h1 class="folder-title">Data Arsip</h1>
            <span class="kode-badge">{{ $kode }}</span>
        </div>
        <p class="folder-desc">Inventaris lengkap dokumen fisik dan rekam digital yang berada di bawah naungan klasifikasi ini.</p>
    </div>

    {{-- 🌟 2. SMART ACTION BAR 🌟 --}}
    <form action="{{ route('arsip.folder.isi', $kode) }}" method="GET" id="smartActionBar" class="smart-action-bar">
        <div class="d-flex justify-content-between align-items-center w-100 flex-wrap gap-3">
            <div class="action-buttons">
                <a href="{{ route('arsip.cetak_pdf', $kode) }}" target="_blank" class="btn-action btn-pdf">
                    <i class="fa-solid fa-file-pdf"></i> Cetak Laporan
                </a>
                
                @can('admin')
                <a href="{{ route('arsip.create', $kode) }}" class="btn-action btn-add">
                    <i class="fa-solid fa-plus"></i> Arsip Baru
                </a>
                @endcan
                
                <a href="{{ route('arsip.folders') }}" class="btn-action btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="search-area ms-md-auto">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="search" class="search-input shadow-sm" placeholder="Telusuri dokumen spesifik..." value="{{ request('search') }}">
                <button type="submit" class="btn-search">Cari</button>
            </div>
        </div>

        <div class="bottom-bar-actions mt-1">
            
            {{-- 🔥 MENAMBAHKAN 4 KOTAK FILTER DENGAN GAP-2 AGAR LEBIH RAPI 🔥 --}}
            <div class="d-flex gap-2 align-items-center flex-wrap w-100">
                <div class="entries-capsule shadow-sm">
                    <span class="text-muted" style="font-size: 12px; font-weight: 600;">Tampilkan:</span>
                    <select name="per_page" class="form-select form-select-sm entries-select" style="min-width: 80px;" onchange="this.form.submit();">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                        <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>Semua Data</option>
                    </select>
                </div>

                <div class="entries-capsule shadow-sm">
                    <span class="text-muted" style="font-size: 12px; font-weight: 600;"><i class="fa-solid fa-filter"></i> Status JRA:</span>
                    <select name="filter_retensi" class="form-select form-select-sm entries-select filter-select" onchange="this.form.submit();">
                        <option value="semua" {{ request('filter_retensi') == 'semua' ? 'selected' : '' }}>Semua Arsip</option>
                        <option value="aktif" {{ request('filter_retensi') == 'aktif' ? 'selected' : '' }}>Aktif (Masih Berlaku)</option>
                        <option value="inaktif" {{ request('filter_retensi') == 'inaktif' ? 'selected' : '' }}>Inaktif (Jatuh Tempo)</option>
                    </select>
                </div>

                <div class="entries-capsule shadow-sm">
                    <span class="text-muted" style="font-size: 12px; font-weight: 600;"><i class="fa-solid fa-paperclip"></i> Status File:</span>
                    <select name="filter_file" class="form-select form-select-sm entries-select filter-select" onchange="this.form.submit();">
                        <option value="semua" {{ request('filter_file') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="ada" {{ request('filter_file') == 'ada' ? 'selected' : '' }}>Tersedia (Ada File)</option>
                        <option value="kosong" {{ request('filter_file') == 'kosong' ? 'selected' : '' }}>Kosong (Tanpa File)</option>
                    </select>
                </div>

                <div class="entries-capsule shadow-sm">
                    <span class="text-muted" style="font-size: 12px; font-weight: 600;"><i class="fa-solid fa-location-dot"></i> Lokasi Fisik:</span>
                    <select name="filter_lokasi" class="form-select form-select-sm entries-select filter-select" onchange="this.form.submit();">
                        <option value="semua" {{ request('filter_lokasi') == 'semua' ? 'selected' : '' }}>Semua Lokasi</option>
                        <option value="Internal" {{ request('filter_lokasi') == 'Internal' ? 'selected' : '' }}>Internal (Ruangan)</option>
                        <option value="Bagian Umum" {{ request('filter_lokasi') == 'Bagian Umum' ? 'selected' : '' }}>Diserahkan ke Bag. Umum</option>
                    </select>
                </div>
            </div>

            @can('admin')
            <div class="d-flex gap-2 align-items-center flex-wrap ms-auto mt-2 mt-lg-0">
                <button type="button" id="btnToggleDeleteMode" class="btn-mode-hapus shadow-sm">
                    <i class="fa-solid fa-list-check"></i> <span>Mode Hapus Massal</span>
                </button>
                
                <button type="button" id="btnBulkDelete" class="btn-bulk-delete" onclick="submitBulkDelete()">
                    <i class="fa-solid fa-trash-can"></i> Eksekusi Hapus (<span id="selectedCount">0</span>)
                </button>
            </div>
            @endcan

        </div>
    </form>

    {{-- 🌟 3. FORM PEMBUNGKUS TABEL 🌟 --}}
    <form id="bulkDeleteForm" action="{{ route('arsip.bulk_delete') }}" method="POST">
        @csrf @method('DELETE')
        
        <div class="table-card">
            <div class="table-responsive">
                <table class="ea-table">
                    <thead>
                        <tr>
                            <th class="col-checkbox d-none">
                                @can('admin')
                                <input type="checkbox" id="checkAll" class="cb-custom" title="Pilih Semua">
                                @endcan
                            </th>
                            <th class="col-no">NO</th>
                            <th class="col-info">INFORMASI DOKUMEN</th>
                            <th>TAHUN & RETENSI</th>
                            <th>KODE KP</th>
                            <th>STATUS FILE</th>
                            <th class="col-aksi">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($arsips as $index => $arsip)
                        
                        {{-- 🔥 LOGIKA CERDAS PERHITUNGAN JRA (ON THE FLY) 🔥 --}}
                        @php
                            $currentYear = (int)date('Y');
                            
                            // 1. Paksa sistem mengekstrak 4 digit angka dari teks tahun_berkas
                            preg_match('/\d{4}/', $arsip->tahun_berkas, $matches);
                            $tahunSistem = !empty($matches) ? (int)$matches[0] : (int)date('Y');
                            
                            // 2. Tangkap angka retensi
                            $retensiAktif = (int)($arsip->retensi_aktif ?? 0);
                            
                            // 3. Kalkulasi matematika
                            $batasAktif = $tahunSistem + $retensiAktif;
                            $isInaktif = $currentYear > $batasAktif;
                            $nasibAkhir = strtolower($arsip->nasib_akhir ?? 'musnah');
                        @endphp

                        <tr class="row-item" style="animation-delay: {{ $index * 0.08 }}s;">
                            
                            <td class="col-checkbox d-none">
                                @can('admin')
                                <input type="checkbox" name="ids[]" value="{{ $arsip->id }}" class="cb-custom check-item">
                                @endcan
                            </td>
                            
                            <td class="col-no text-muted fw-bold" style="font-size: 15px;">
                                {{ ($arsips->currentPage() - 1) * $arsips->perPage() + $index + 1 }}
                            </td>
                            
                            <td>
                                <div style="min-width: 0;">
                                    <div class="doc-title">{{ $arsip->nama_berkas }}</div>
                                    <div class="doc-desc">{{ $arsip->deskripsi_berkas }}</div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 14px; margin-bottom: 2px;">{{ $arsip->tahun_berkas }}</div>
                                
                                {{-- 🌟 BADGE JRA DINAMIS 🌟 --}}
                                @if(!$isInaktif)
                                    <span class="ea-badge-aktif" title="Masih dalam masa aktif s.d tahun {{ $batasAktif }}"><i class="fa-solid fa-shield-check"></i> Aktif (s.d {{ $batasAktif }})</span>
                                @else
                                    @if($nasibAkhir == 'permanen')
                                        <span class="ea-badge-permanen"><i class="fa-solid fa-building-columns"></i> Inaktif (Permanen)</span>
                                    @else
                                        <span class="ea-badge-inaktif"><i class="fa-solid fa-fire"></i> Inaktif (Musnah)</span>
                                    @endif
                                @endif

                                {{-- 🌟 BADGE LOKASI FISIK BERKAS 🌟 --}}
                                <div class="mt-1">
                                    @if(isset($arsip->status_lokasi) && $arsip->status_lokasi == 'Bagian Umum')
                                        <span class="badge-lokasi-umum" title="Berkas fisik sudah dipindahkan ke Bagian Umum">
                                            <i class="fa-solid fa-check-double"></i> Diserahkan ke Bag. Umum
                                        </span>
                                    @else
                                        <span class="badge-lokasi-internal" title="Berkas fisik masih tersimpan di unit kerja">
                                            <i class="fa-solid fa-box-archive"></i> Internal
                                        </span>
                                    @endif
                                </div>

                            </td>
                            
                            <td>
                                <span class="badge-kp"><i class="fa-solid fa-tag"></i> {{ $arsip->kode_arsip }}</span>
                            </td>
                            <td>
                                @if($arsip->file_dokumen)
                                    <a href="{{ asset('storage/' . $arsip->file_dokumen) }}" target="_blank" class="status-file-ada">
                                        <i class="fa-solid fa-file-circle-check fs-6"></i> Tersedia
                                    </a>
                                @else
                                    <span class="status-file-kosong">
                                        <i class="fa-solid fa-file-circle-xmark"></i> Kosong
                                    </span>
                                @endif
                            </td>
                            <td class="col-aksi">
                                <div class="action-group-table">
                                    <button type="button" class="btn-t-view" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $arsip->id }}" title="Lihat Rincian Detail">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </button>

                                    @can('admin')
                                    <a href="{{ route('arsip.edit', $arsip->id) }}" class="btn-t-edit" title="Edit Data">
                                        <i class="fa-solid fa-pen-clip"></i>
                                    </a>
                                    <button type="button" class="btn-t-del" title="Hapus Data" onclick="confirmSingleDelete({{ $arsip->id }})">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>

                        {{-- 🌟 MODAL DETAIL COMPACT / SPLIT PANEL 🌟 --}}
                        <div class="modal fade" id="modalDetail{{ $arsip->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                                    <div class="modal-header border-0 p-3 px-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; border-bottom: 3px solid #C8A35A !important;">
                                        <h5 class="modal-title fw-bold" style="letter-spacing: 0.5px; font-size: 15px;">
                                            <i class="fa-solid fa-magnifying-glass-chart me-2" style="color: #C8A35A;"></i> Rincian Arsip
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <div class="row g-0">
                                            {{-- PANEL KIRI: INFO UTAMA --}}
                                            <div class="col-md-5 p-4" style="background: #f8fafc; border-right: 1px solid #e2e8f0;">
                                                <h6 class="fw-bold text-dark mb-3" style="font-size: 14px;"><i class="fa-solid fa-tags text-muted me-1"></i> Identitas Dokumen</h6>

                                                <div class="d-flex flex-column gap-1">
                                                    <div class="detail-item">
                                                        <span class="detail-label">Kode KP</span>
                                                        <span class="detail-value fw-bold" style="color: #C8A35A;">{{ $arsip->kode_arsip }}</span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="detail-label">Tahun</span>
                                                        <span class="detail-value fw-bold">{{ $arsip->tahun_berkas }}</span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="detail-label">Retensi Aktif</span>
                                                        <span class="detail-value">{{ $retensiAktif }} Tahun (s.d {{ $batasAktif }})</span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="detail-label">Status JRA</span>
                                                        <span class="detail-value">
                                                            @if(!$isInaktif)
                                                                <span class="badge bg-success rounded-pill px-2"><i class="fa-solid fa-shield-check"></i> Aktif</span>
                                                            @else
                                                                @if($nasibAkhir == 'permanen')
                                                                    <span class="badge bg-primary rounded-pill px-2"><i class="fa-solid fa-building-columns"></i> Permanen</span>
                                                                @else
                                                                    <span class="badge bg-danger rounded-pill px-2"><i class="fa-solid fa-fire"></i> Musnah</span>
                                                                @endif
                                                            @endif
                                                        </span>
                                                    </div>
                                                    
                                                    {{-- INFO LOKASI FISIK DI MODAL DETAIL --}}
                                                    <div class="detail-item">
                                                        <span class="detail-label">Lokasi Fisik</span>
                                                        <span class="detail-value">
                                                            @if(isset($arsip->status_lokasi) && $arsip->status_lokasi == 'Bagian Umum')
                                                                <span class="badge bg-info text-dark rounded-pill px-2"><i class="fa-solid fa-check-double"></i> Bag. Umum</span>
                                                            @else
                                                                <span class="badge bg-secondary rounded-pill px-2"><i class="fa-solid fa-box-archive"></i> Internal</span>
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <div class="detail-item">
                                                        <span class="detail-label">Jumlah Berkas</span>
                                                        <span class="detail-value">{{ $arsip->jumlah_berkas ?? '1' }} Eks</span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <span class="detail-label">Warna Berkas</span>
                                                        <span class="detail-value">{{ $arsip->warna_berkas ?: '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- PANEL KANAN: JUDUL, DESKRIPSI, LAMPIRAN --}}
                                            <div class="col-md-7 p-4 d-flex flex-column">
                                                <div class="mb-3">
                                                    <span style="font-size: 11px; font-weight: 700; color: #C8A35A; text-transform: uppercase; letter-spacing: 1px;">Judul Berkas</span>
                                                    <h5 class="fw-bold text-dark mt-1 mb-0" style="line-height: 1.4; font-size: 16px;">{{ $arsip->nama_berkas }}</h5>
                                                </div>

                                                <div class="mb-auto">
                                                    <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Deskripsi / Uraian</span>
                                                    <div class="mt-1 text-muted desc-scroll" style="font-size: 12px; line-height: 1.6;">
                                                        {!! $arsip->deskripsi_berkas ? nl2br(e($arsip->deskripsi_berkas)) : '<span class="fst-italic">Tidak ada uraian catatan tambahan.</span>' !!}
                                                    </div>
                                                </div>

                                                <div class="mt-3 p-3 rounded-3 border {{ $arsip->file_dokumen ? 'file-box-ada' : 'file-box-kosong' }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <div class="file-box-title" style="font-size: 11px; font-weight: 700; margin-bottom: 2px;">
                                                                <i class="fa-solid fa-paperclip"></i> File Digital
                                                            </div>
                                                            <div style="font-size: 12px; font-weight: 600;" class="detail-value">
                                                                {{ $arsip->file_dokumen ? 'File Tersedia di Server' : 'Tidak Ada File Unggahan' }}
                                                            </div>
                                                        </div>
                                                        @if($arsip->file_dokumen)
                                                            <a href="{{ asset('storage/' . $arsip->file_dokumen) }}" target="_blank" class="btn btn-sm btn-success fw-bold px-3 rounded-pill shadow-sm" style="font-size: 11px;">
                                                                <i class="fa-solid fa-download"></i> Unduh
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="text-end mt-3">
                                                    <span style="font-size: 10px; color: #94a3b8;"><i class="fa-solid fa-clock-rotate-left"></i> Diinput: {{ $arsip->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5" style="border: none;">
                                <div style="color: #cbd5e1; font-size: 60px; margin-bottom: 20px;"><i class="fa-solid fa-box-open"></i></div>
                                <h5 class="fw-bold text-dark mb-2">Folder Ini Kosong</h5>
                                <p class="text-muted">Belum ada dokumen yang diarsipkan ke dalam sistem.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <div class="text-muted small fw-medium" style="color: #94a3b8 !important;">
                    Menampilkan <b class="page-count-text">{{ $arsips->firstItem() ?? 0 }}</b> hingga <b class="page-count-text">{{ $arsips->lastItem() ?? 0 }}</b> dari total <b class="page-count-text">{{ $arsips->total() }}</b> entri arsip.
                </div>
                <div class="pagination-links">
                    {{ $arsips->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </form>
    
    @can('admin')
    <form id="singleDeleteForm" method="POST" style="display: none;">
        @csrf @method('DELETE')
    </form>
    @endcan

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const actionBar = document.getElementById('smartActionBar');
        let isScrolled = false;
        let hideTimeout; 

        window.addEventListener('scroll', () => {
            if (window.scrollY > 250) {
                isScrolled = true;
                if (!actionBar.matches(':hover')) { actionBar.classList.add('hide-up'); actionBar.classList.remove('peek-down'); }
            } else {
                isScrolled = false;
                actionBar.classList.remove('hide-up'); actionBar.classList.remove('peek-down');
            }
        });

        document.addEventListener('mousemove', (e) => {
            if (!isScrolled) return;
            if (e.clientY < 150 || actionBar.matches(':hover')) {
                clearTimeout(hideTimeout); 
                actionBar.classList.remove('hide-up'); actionBar.classList.add('peek-down');
            } else {
                clearTimeout(hideTimeout);
                hideTimeout = setTimeout(() => {
                    if (!actionBar.matches(':hover')) { actionBar.classList.add('hide-up'); actionBar.classList.remove('peek-down'); }
                }, 500); 
            }
        });

        const btnToggleDeleteMode = document.getElementById('btnToggleDeleteMode');
        const checkboxCols = document.querySelectorAll('.col-checkbox');
        const checkAll = document.getElementById('checkAll');
        const checkItems = document.querySelectorAll('.check-item');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const selectedCountText = document.getElementById('selectedCount');
        let deleteModeActive = false;

        if (btnToggleDeleteMode) {
            btnToggleDeleteMode.addEventListener('click', function() {
                deleteModeActive = !deleteModeActive;
                this.classList.toggle('active');
                
                if (deleteModeActive) {
                    this.innerHTML = '<i class="fa-solid fa-xmark"></i> <span>Batal Hapus</span>';
                    checkboxCols.forEach(col => col.classList.remove('d-none'));
                } else {
                    this.innerHTML = '<i class="fa-solid fa-list-check"></i> <span>Mode Hapus Massal</span>';
                    checkboxCols.forEach(col => col.classList.add('d-none'));
                    
                    if(checkAll) checkAll.checked = false;
                    checkItems.forEach(item => {
                        item.checked = false;
                        item.closest('tr').classList.remove('row-selected');
                    });
                    updateBulkDeleteButton();
                }
            });
        }

        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.check-item:checked').length;
            selectedCountText.innerText = checkedCount;

            checkItems.forEach(item => {
                const tr = item.closest('tr');
                if(item.checked) {
                    tr.classList.add('row-selected');
                } else {
                    tr.classList.remove('row-selected');
                }
            });

            if (checkedCount > 0) {
                btnBulkDelete.classList.add('show-anim');
            } else {
                btnBulkDelete.classList.remove('show-anim');
                if(checkAll) checkAll.checked = false; 
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkItems.forEach(item => {
                    item.checked = this.checked;
                });
                updateBulkDeleteButton();
            });
        }

        checkItems.forEach(item => {
            item.addEventListener('change', function() {
                updateBulkDeleteButton();
                if(checkAll) {
                    const allChecked = document.querySelectorAll('.check-item:checked').length === checkItems.length;
                    checkAll.checked = allChecked;
                }
            });
        });
    });

    function submitBulkDelete() {
        const count = document.getElementById('selectedCount').innerText;
        Swal.fire({
            title: 'Buang ' + count + ' Dokumen?',
            text: "Arsip yang dipilih akan dipindahkan ke Tong Sampah!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', 
            cancelButtonColor: '#475569', 
            confirmButtonText: '<i class="fa-solid fa-trash-can me-1"></i> Ya, Buang!',
            cancelButtonText: 'Batal',
            reverseButtons: true, 
            backdrop: `rgba(15, 23, 42, 0.4)`, 
            customClass: { popup: 'border border-light shadow-lg', title: 'fs-4 fw-bold' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    }

    function confirmSingleDelete(id) {
        Swal.fire({
            title: 'Pindahkan Arsip?',
            text: "Dokumen ini akan dipindahkan sementara ke Tong Sampah.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#475569',
            confirmButtonText: '<i class="fa-solid fa-trash-can me-1"></i> Ya, Pindahkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            backdrop: `rgba(15, 23, 42, 0.4)`,
            customClass: { popup: 'border border-light shadow-lg', title: 'fs-4 fw-bold' }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('singleDeleteForm');
                form.action = `/arsip/${id}`; 
                form.submit();
            }
        });
    }
</script>
@endpush