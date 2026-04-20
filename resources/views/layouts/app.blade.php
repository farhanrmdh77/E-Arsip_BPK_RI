<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Arsip Digital BPK RI')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        
        /* 🔥 PERBAIKAN: transition background dihapus dari sini agar tidak memicu animasi saat pindah halaman 🔥 */
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f9; overflow-x: hidden; }

        /* PROGRESS BAR YOUTUBE STYLE */
        #top-progress-bar {
            position: fixed; top: 0; left: 0; width: 0%; height: 4px;
            background: linear-gradient(90deg, #C8A35A, #fde68a, #ffedd5);
            z-index: 1060; transition: width 0.4s ease, opacity 0.3s ease;
            opacity: 0; pointer-events: none; box-shadow: 0 0 15px rgba(200, 163, 90, 0.8);
        }

        /* SIDEBAR ENTERPRISE */
        .sidebar {
            width: 280px; height: 100vh; position: fixed; left: 0; top: 0;
            background: #0b1120; display: flex; flex-direction: column; z-index: 1050;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 4px 0 24px rgba(0,0,0,0.05);
        }

        .brand-section { padding: 30px 25px; display: flex; align-items: center; gap: 15px; position: relative; }
        .brand-logo-img { width: 48px; height: 48px; object-fit: contain; filter: drop-shadow(0 0 8px rgba(200, 163, 90, 0.4)); }
        
        .brand-name { 
            font-size: 20px; font-weight: 800; margin: 0; letter-spacing: 0.5px;
            background: linear-gradient(110deg, #fde68a 0%, #C8A35A 30%, #ffffff 50%, #C8A35A 70%, #fde68a 100%);
            background-size: 200% auto;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            animation: shineText 4s linear infinite;
        }
        @keyframes shineText { to { background-position: 200% center; } }

        .nav-menu { flex-grow: 1; padding: 20px 15px; overflow-y: auto; }
        .nav-menu::-webkit-scrollbar { width: 4px; }
        .nav-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        .nav-link-item { 
            display: flex; align-items: center; gap: 15px; padding: 14px 20px; color: #94a3b8; 
            text-decoration: none; border-radius: 12px; font-weight: 500; font-size: 14px; margin-bottom: 8px; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid transparent;
        }
        .nav-link-item:hover { background: rgba(200, 163, 90, 0.08); color: #C8A35A; transform: translateX(6px); border-color: rgba(200, 163, 90, 0.1); }
        .nav-link-item:active { transform: scale(0.96) translateX(6px); }
        .nav-link-item.active { 
            background: linear-gradient(135deg, #C8A35A 0%, #9c7b39 100%); color: #ffffff; 
            box-shadow: 0 8px 20px rgba(200, 163, 90, 0.25); transform: translateX(6px); border-color: transparent;
        }
        .nav-link-item i { font-size: 18px; width: 25px; text-align: center; }

        .sidebar-footer { flex-shrink: 0; padding: 25px 20px; background: rgba(0, 0, 0, 0.15); border-top: 1px solid rgba(255, 255, 255, 0.05); }
        .user-card-premium { display: flex; align-items: center; gap: 12px; background: #1e293b; padding: 12px; border-radius: 14px; margin-bottom: 15px; border: 1px solid rgba(200, 163, 90, 0.15); cursor: pointer; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .user-card-premium:hover { border-color: #C8A35A; background: #26334d; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.2); }
        .user-avatar { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; border: 2px solid #C8A35A; }
        .user-info { overflow: hidden; }
        
        .user-name { font-size: 13px; font-weight: 700; color: #ffffff !important; display: block; white-space: nowrap; text-overflow: ellipsis; }
        .user-role { font-size: 10px; color: #C8A35A !important; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; }

        .btn-logout-premium { width: 100%; background: rgba(239, 68, 68, 0.08); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); padding: 12px; border-radius: 12px; font-weight: 600; font-size: 13px; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.3s ease; }
        .btn-logout-premium:hover { background: #ef4444; color: white; box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3); }

        /* MAIN CONTENT AREA */
        .main-content { margin-left: 280px; min-height: 100vh; display: flex; flex-direction: column; transition: margin-left 0.4s ease; }
        .content-body { padding: 30px 40px; flex-grow: 1; animation: pageFadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes pageFadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: none; } }

        /* NAVBAR ATAS KELAS DUNIA */
        .top-navbar {
            background: rgba(255, 255, 255, 0.75); 
            backdrop-filter: blur(24px) saturate(200%); -webkit-backdrop-filter: blur(24px) saturate(200%);
            padding: 16px 40px; border-bottom: 1px solid rgba(255, 255, 255, 0.6);
            display: flex; justify-content: space-between; align-items: center;
            position: -webkit-sticky; position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03); transition: all 0.4s ease;
        }

        .navbar-left { display: flex; align-items: center; gap: 20px; }
        .header-title-text { font-size: 22px; font-weight: 800; margin: 0; letter-spacing: -0.5px; background: linear-gradient(135deg, #0f172a 0%, #475569 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .user-greeting { font-size: 13px; color: #64748b; font-weight: 500; margin-top: 2px; display: flex; align-items: center; gap: 6px; }
        .greeting-hand { display: inline-block; animation: waveHand 2.5s infinite; transform-origin: 70% 70%; }
        @keyframes waveHand { 0% { transform: rotate(0deg); } 10% { transform: rotate(14deg); } 20% { transform: rotate(-8deg); } 30% { transform: rotate(14deg); } 40% { transform: rotate(-4deg); } 50% { transform: rotate(10deg); } 60%, 100% { transform: rotate(0deg); } }
        .navbar-right { display: flex; align-items: center; gap: 14px; }

        .btn-theme-toggle { background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8); color: #475569; width: 42px; height: 42px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 16px; cursor: pointer; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 4px 10px rgba(0,0,0,0.03); position: relative; }
        .btn-theme-toggle:hover { transform: translateY(-3px) scale(1.05); border-color: #C8A35A; color: #C8A35A; box-shadow: 0 8px 20px rgba(200, 163, 90, 0.15); }
        .btn-qr-scan { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); color: #0284c7; border-color: #bae6fd; }
        .btn-qr-scan:hover { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; border-color: transparent; box-shadow: 0 8px 20px rgba(2, 132, 199, 0.25); }
        
        .btn-notif { background: #ffffff; color: #64748b; }
        .notif-dot { position: absolute; top: 10px; right: 10px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 0 2px #ffffff; }
        .btn-notif:hover .notif-dot { animation: bounce 1s infinite; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-3px); } }

        .notif-dropdown-menu { width: 340px; border-radius: 16px; padding: 0; overflow: hidden; margin-top: 15px !important; animation: dropFade 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes dropFade { from { opacity: 0; transform: translateY(-15px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .notif-header { background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .notif-body::-webkit-scrollbar { width: 4px; }
        .notif-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        .notif-item { border-bottom: 1px solid #f1f5f9; white-space: normal; transition: 0.2s; background: #ffffff; text-decoration: none; display: block; }
        .notif-item:hover { background: #f8fafc; text-decoration: none; }
        .notif-item:last-child { border-bottom: none; }
        .notif-title { font-size: 13px; color: #1e293b; }
        .notif-desc { font-size: 11.5px; line-height: 1.5; margin-top: 4px; color: #64748b;}

        .premium-date-badge { background: rgba(255, 255, 255, 0.9); color: #1e293b; padding: 10px 16px; border-radius: 12px; font-weight: 600; font-size: 13px; border: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 4px 10px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 10px; transition: 0.3s; }
        .premium-date-badge:hover { box-shadow: 0 6px 15px rgba(0,0,0,0.05); transform: translateY(-2px); }
        
        .live-indicator { width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px rgba(16,185,129,0.8); animation: pulseLive 2s infinite; }
        @keyframes pulseLive { 0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.7); } 70% { box-shadow: 0 0 0 8px rgba(16,185,129,0); } 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); } }

        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1040; opacity: 0; transition: opacity 0.3s ease; }
        
        /* ======================================================= */
        /* 🌟 DARK MODE STYLES PENGHANCUR PUTIH 🌟                 */
        /* ======================================================= */
        body.dark-mode { background-color: #040914 !important; color: #f8fafc !important; }
        
        /* Navbar Dark Mode */
        body.dark-mode .top-navbar { background: rgba(11, 17, 32, 0.8) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; box-shadow: 0 10px 40px rgba(0,0,0,0.5); }
        body.dark-mode .header-title-text { background: none; -webkit-text-fill-color: #f8fafc; color: #f8fafc; }
        body.dark-mode .user-greeting { color: #94a3b8; }
        body.dark-mode .btn-theme-toggle { background: #1e293b; border-color: #334155; color: #cbd5e1; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        body.dark-mode .btn-theme-toggle:hover { border-color: #C8A35A; color: #fde68a; background: #0f172a; box-shadow: 0 8px 20px rgba(200, 163, 90, 0.1); }
        body.dark-mode .btn-theme-toggle.btn-qr-scan { background: #0c4a6e; color: #38bdf8; border-color: #0284c7; }
        body.dark-mode .btn-theme-toggle.btn-qr-scan:hover { background: #0284c7; color: #fff; }
        body.dark-mode .notif-dot { box-shadow: 0 0 0 2px #1e293b; }
        body.dark-mode .premium-date-badge { background: #1e293b; border-color: #334155; color: #f1f5f9; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }

        body.dark-mode .notif-dropdown-menu { background-color: #0f172a !important; border: 1px solid #1e293b !important; box-shadow: 0 10px 40px rgba(0,0,0,0.8) !important; }
        body.dark-mode .notif-header { background-color: #1e293b !important; border-bottom-color: #334155 !important; }
        body.dark-mode .notif-item { background-color: #0f172a !important; border-bottom-color: #1e293b !important; }
        body.dark-mode .notif-item:hover { background-color: #162032 !important; }
        body.dark-mode .notif-title { color: #f8fafc !important; }
        body.dark-mode .notif-desc { color: #cbd5e1 !important; }
        body.dark-mode .dropdown-item { color: #cbd5e1 !important; }

        /* Komponen Card dan Box */
        body.dark-mode .bg-white, body.dark-mode .bg-light { background-color: #0f172a !important; border-color: #1e293b !important; }
        body.dark-mode .card, body.dark-mode .card-body, body.dark-mode .table-responsive,
        body.dark-mode .premium-card, body.dark-mode .ea-card, body.dark-mode .ea-section, 
        body.dark-mode .table-card, body.dark-mode .folder-card, body.dark-mode .config-card { 
            background-color: #0f172a !important; border-color: #1e293b !important; box-shadow: 0 5px 20px rgba(0,0,0,0.5); 
        }
        
        body.dark-mode .search-input, body.dark-mode .form-input-custom, body.dark-mode .form-control, body.dark-mode .ea-input, body.dark-mode .global-input { background-color: #1e293b !important; border-color: #334155 !important; color: #f8fafc !important; }
        body.dark-mode .search-input:focus, body.dark-mode .ea-input:focus, body.dark-mode .global-input:focus { border-color: #C8A35A !important; background-color: #0b1120 !important; color: #ffffff !important;}
        body.dark-mode input[type="file"]::file-selector-button { background-color: #0f172a !important; color: #f8fafc !important; border: 1px solid #334155 !important; border-right-color: #1e293b !important; transition: 0.3s; }
        body.dark-mode input[type="file"]::file-selector-button:hover { background-color: #1e293b !important; }
        body.dark-mode .ea-update-banner { background-color: #1e293b !important; border-left-color: #C8A35A !important; }
        
        body.dark-mode .smart-action-bar { background-color: rgba(15, 23, 42, 0.85) !important; border-color: #1e293b !important; }
        body.dark-mode .btn-back { background-color: #1e293b !important; border-color: #334155 !important; }
        body.dark-mode .modal-content, body.dark-mode .ea-modal-content { background-color: #0f172a !important; border-color: transparent !important; }
        body.dark-mode .modal-body, body.dark-mode .modal-footer { background-color: #0f172a !important; border-top-color: #1e293b !important; }
        body.dark-mode .detail-box { background-color: #1e293b !important; border-color: transparent !important; }
        body.dark-mode .alert-info { background-color: #1e293b !important; border-color: transparent !important; color: #cbd5e1 !important; }

        /* TABEL 1: TABEL DATA ARSIP & FOLDER */
        body.dark-mode .ea-table { 
            --bs-table-bg: #0f172a !important; 
            color: #f8fafc !important; 
            border-color: #1e293b !important; 
        }
        body.dark-mode .ea-table th { 
            background-color: #1e293b !important; 
            border-bottom-color: #1e293b !important; 
            color: #94a3b8 !important; 
        }
        body.dark-mode .ea-table td { 
            background-color: #0f172a !important; 
            border-color: #1e293b !important; 
            color: #cbd5e1 !important; 
        }
        body.dark-mode .ea-table tbody tr { background-color: #0f172a !important; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        body.dark-mode .ea-table tbody tr:hover { background-color: #1e293b !important; }
        
        body.dark-mode .ea-table td span:not(.badge),
        body.dark-mode .ea-table td strong,
        body.dark-mode .ea-table td b,
        body.dark-mode .ea-table td [style*="color"] {
            color: #ffffff !important;
        }

        body.dark-mode .ea-table td span[style*="background"],
        body.dark-mode .ea-table td div[style*="background"],
        body.dark-mode .ea-table td span[class*="bg-"] {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border: 1px solid #334155 !important;
        }

        body.dark-mode .ea-table td .badge { background-color: #1e293b !important; border: 1px solid #334155 !important; color: #f8fafc !important; }
        body.dark-mode .ea-table td .badge i { color: #C8A35A !important; }

        /* TABEL 2: TABEL RIWAYAT LOG */
        body.dark-mode .table, 
        body.dark-mode .table-bordered { 
            --bs-table-bg: #0f172a !important; 
            background-color: #0f172a !important; 
            border: none !important; 
        }
        body.dark-mode .table th, 
        body.dark-mode .table td,
        body.dark-mode .table-bordered th, 
        body.dark-mode .table-bordered td { 
            background-color: transparent !important; 
            border: none !important; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; 
            color: #ffffff !important; 
        }
        body.dark-mode .table tbody tr:last-child td { border-bottom: none !important; }
        body.dark-mode .table-hover > tbody > tr:hover > td { background-color: #162032 !important; }
        body.dark-mode .table-striped > tbody > tr:nth-of-type(odd) > td { background-color: rgba(255, 255, 255, 0.02) !important; }

        body.dark-mode .table td p,
        body.dark-mode .table td strong,
        body.dark-mode .table td b { color: #ffffff !important; }
        
        body.dark-mode .table td small, 
        body.dark-mode .table td .text-muted,
        body.dark-mode .table td i { color: #ffffff !important; }

        body.dark-mode .table td .badge,
        body.dark-mode .table td span[class*="bg-"],
        body.dark-mode .table td span[style*="background"] {
            color: #ffffff !important; 
            font-weight: 600 !important;
            border: none !important;
        }

        body.dark-mode .user-card, body.dark-mode .user-list-item { background-color: #1e293b !important; border-color: transparent !important; }
        
        body.dark-mode .custom-dashboard, body.dark-mode .custom-page, body.dark-mode .main-content { color: #f1f5f9 !important; }
        
        body.dark-mode h1, body.dark-mode h2, body.dark-mode h3, body.dark-mode h4, body.dark-mode h5, body.dark-mode h6,
        body.dark-mode .form-label, body.dark-mode .col-form-label, body.dark-mode legend, 
        body.dark-mode .ea-title, body.dark-mode .ea-card-info h2, body.dark-mode .ea-section-header, 
        body.dark-mode .activity-user, body.dark-mode .doc-title, body.dark-mode .detail-value, 
        body.dark-mode .folder-title, body.dark-mode .search-title, body.dark-mode .text-dark,
        body.dark-mode .card-title-custom, body.dark-mode .fc-title, body.dark-mode .stat-value,
        body.dark-mode .card-title { color: #ffffff !important; }

        body.dark-mode p:not(.table p), body.dark-mode label, body.dark-mode li, 
        body.dark-mode .text-muted:not(.table .text-muted), body.dark-mode .text-secondary,
        body.dark-mode small:not(.table small), body.dark-mode .small, body.dark-mode .form-text, 
        body.dark-mode .ea-update-banner, body.dark-mode .ea-card-info h6, body.dark-mode .activity-desc, 
        body.dark-mode .doc-desc, body.dark-mode .detail-label, body.dark-mode .activity-time, body.dark-mode .folder-desc,
        body.dark-mode .stat-label, body.dark-mode .kp-stat-item { color: #cbd5e1 !important; }

        body.dark-mode div:not(.table div)[style*="background: #ffffff"], 
        body.dark-mode div:not(.table div)[style*="background-color: #ffffff"],
        body.dark-mode div:not(.table div)[style*="background: #f8fafc"] {
            background-color: #1e293b !important; border-color: transparent !important; color: #f8fafc !important;
        }

        #reader { width: 100%; border-radius: 12px; overflow: hidden; border: none !important; }
        #reader video { border-radius: 12px; object-fit: cover; }

        /* ======================================================================================== */
        /* 📱 RESPONSIVE MOBILE CSS */
        /* ======================================================================================== */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .sidebar-overlay.active { display: block; opacity: 1; }
            .main-content { margin-left: 0; }
            
            .top-navbar { padding: 15px; flex-direction: column; align-items: stretch; gap: 12px; }
            .navbar-left { width: 100%; display: flex; justify-content: flex-start; align-items: center; gap: 12px; }
            .header-title-text { font-size: 18px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            
            .navbar-right { width: 100%; display: flex; justify-content: space-between; align-items: center; gap: 8px; }
            .premium-date-badge { display: flex !important; flex-grow: 1; justify-content: center; font-size: 12px; padding: 8px 10px; height: 42px; }
            .dropdown { display: flex !important; }
            .btn-theme-toggle { width: 42px; height: 42px; font-size: 16px; flex-shrink: 0; }
            
            .content-body { padding: 20px; }
            
            .notif-dropdown-menu { position: fixed !important; top: 130px !important; left: 15px !important; right: 15px !important; width: auto !important; max-width: none !important; z-index: 1100; }
            .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        }
    </style>
    @stack('styles')
</head>
<body>
    
    {{-- 🔥 SCRIPT PENCEGAH KILAT PUTIH (BLOCKING SCRIPT) 🔥 --}}
    {{-- Script ini sengaja ditaruh langsung di bawah body agar langsung dieksekusi sebelum halaman digambar --}}
    <script>
        if (localStorage.getItem('earsip_theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <div id="top-progress-bar"></div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebarMenu">
        <div class="brand-section">
            @php $appLogo = \App\Models\Setting::getAppLogo(); @endphp
            @if($appLogo) <img src="{{ $appLogo }}" alt="Logo" class="brand-logo-img">
            @else <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d2/BPK_insignia.svg/330px-BPK_insignia.svg.png" class="brand-logo-img"> @endif
            <h2 class="brand-name">E-Arsip BPK</h2>
        </div>

        <nav class="nav-menu">
            <a href="{{ route('arsip.dashboard') }}" class="nav-link-item trigger-loader {{ request()->routeIs('arsip.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
            <a href="{{ route('arsip.folders') }}" class="nav-link-item trigger-loader {{ request()->routeIs('arsip.folders') ? 'active' : '' }}"><i class="fa-solid fa-boxes-stacked"></i> Gudang Folder</a>

            @can('admin')
            <a href="{{ route('users.index') }}" class="nav-link-item trigger-loader {{ request()->routeIs('users.index') ? 'active' : '' }}"><i class="fa-solid fa-user-shield"></i> Pengguna</a>
            <a href="{{ route('arsip.trash') }}" class="nav-link-item trigger-loader {{ request()->routeIs('arsip.trash') ? 'active' : '' }}"><i class="fa-solid fa-trash-arrow-up"></i> Kelola Sampah</a>
            <a href="{{ route('logs.index') }}" class="nav-link-item trigger-loader {{ request()->routeIs('logs.index') ? 'active' : '' }}"><i class="fa-solid fa-fingerprint"></i> Riwayat</a>
            <a href="{{ route('settings.index') }}" class="nav-link-item trigger-loader {{ request()->routeIs('settings.index') ? 'active' : '' }}"><i class="fa-solid fa-sliders"></i> Pengaturan</a>
            @endcan
        </nav>

        <div class="sidebar-footer">
            <div class="user-card-premium" data-bs-toggle="modal" data-bs-target="#modalUpdateAvatar">
                @if(auth()->user()->avatar) <img src="{{ asset(auth()->user()->avatar) }}" alt="Avatar" class="user-avatar">
                @else <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=C8A35A&color=fff" alt="Avatar" class="user-avatar"> @endif
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">{{ auth()->user()->role ?? 'Administrator' }}</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout-premium"><i class="fa-solid fa-power-off"></i> Keluar Sistem</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="top-navbar">
            
            <div class="navbar-left">
                <button type="button" class="btn-theme-toggle d-lg-none" id="btnToggleSidebar" title="Buka Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div>
                    <h5 class="header-title-text">
                        @yield('header_title', 'Menu Sistem')
                    </h5>
                    <span class="user-greeting">
                        Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}!
                    </span>
                </div>
            </div>
            
            <div class="navbar-right">
                <div class="premium-date-badge">
                    <span class="live-indicator" title="Sistem Aktif"></span>
                    <span>{{ date('d M Y') }}</span>
                </div>

                @can('admin')
                <div class="dropdown">
                    <button type="button" class="btn-theme-toggle btn-notif" id="dropdownNotifBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi Sistem">
                        <i class="fa-regular fa-bell"></i>
                        <span class="notif-dot"></span>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end notif-dropdown-menu shadow-lg border-0" aria-labelledby="dropdownNotifBtn">
                        <li class="notif-header d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="fw-bold" style="font-size: 14px; color: inherit;">Aktivitas Terbaru</span>
                            <a href="{{ route('logs.index') }}" class="text-decoration-none small" style="color: #C8A35A; font-weight: 600;">Lihat Log</a>
                        </li>
                        <div class="notif-body" style="max-height: 320px; overflow-y: auto;">
                            @php
                                $latestLogs = \App\Models\ActivityLog::with('user')->latest()->limit(5)->get();
                            @endphp
                            
                            @forelse($latestLogs as $log)
                            <li>
                                @php
                                    $targetUrl = '#';
                                    $activity = strtolower($log->activity);
                                    if (str_contains($activity, 'folder') && !str_contains($activity, 'hapus')) {
                                        $targetUrl = route('arsip.folders');
                                    } elseif (str_contains($activity, 'hapus') || str_contains($activity, 'buang') || str_contains($activity, 'massal')) {
                                        $targetUrl = route('arsip.trash');
                                    } elseif (str_contains($activity, 'berkas') || str_contains($activity, 'arsip') || str_contains($activity, 'excel') || str_contains($activity, 'pulihkan')) {
                                        $targetUrl = route('arsip.folders'); 
                                    }
                                    if (str_contains($activity, 'permanen')) { $targetUrl = '#'; }
                                @endphp
                                <a class="dropdown-item notif-item d-flex flex-column py-3 px-4" href="{{ $targetUrl }}">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #C8A35A;"></div>
                                        <span class="notif-title fw-bold text-truncate">{{ $log->activity }}</span>
                                    </div>
                                    <span class="notif-desc">{{ $log->description }}</span>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span style="font-size: 10px; color: #94a3b8;"><i class="fa-solid fa-user-pen"></i> {{ $log->user->name ?? 'Sistem' }}</span>
                                        <span style="font-size: 10px; color: #94a3b8; font-weight: 600;">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            </li>
                            @empty
                            <li><span class="dropdown-item text-center py-4 small" style="color: #94a3b8;">Belum ada aktivitas.</span></li>
                            @endforelse
                        </div>
                    </ul>
                </div>
                @endcan

                <button type="button" class="btn-theme-toggle btn-qr-scan" data-bs-toggle="modal" data-bs-target="#modalScanQR" title="Scan Barcode / QR">
                    <i class="fa-solid fa-qrcode"></i>
                </button>

                <button id="darkModeToggle" class="btn-theme-toggle" title="Ganti Suasana">
                    <i class="fa-solid fa-moon"></i>
                </button>
            </div>
        </div>

        <div class="content-body">
            @yield('content')
        </div>
    </div>

    {{-- MODAL KAMERA SCANNER QR --}}
    <div class="modal fade" id="modalScanQR" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ea-modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; border-bottom: 3px solid #0284c7 !important;">
                    <h5 class="modal-title fw-bold" style="margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-camera-retro"></i> Pindai Map Fisik
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="text-muted small mb-3">Arahkan kamera HP ke stiker QR yang menempel di Map/Bantex BPK.</p>
                    <div id="reader"></div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4">
                    <button type="button" class="btn btn-light fw-bold w-100" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup Kamera</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            const btnToggleSidebar = document.getElementById('btnToggleSidebar');
            const sidebarMenu = document.getElementById('sidebarMenu');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function toggleMenu() {
                sidebarMenu.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
            }

            if(btnToggleSidebar) btnToggleSidebar.addEventListener('click', toggleMenu);
            if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleMenu);

            let html5QrcodeScanner;
            const scanModal = document.getElementById('modalScanQR');

            scanModal.addEventListener('shown.bs.modal', function () {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader", { fps: 10, qrbox: {width: 250, height: 250}, rememberLastUsedCamera: true }, false
                );
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            });

            scanModal.addEventListener('hidden.bs.modal', function () {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.clear().catch(error => console.error("Gagal mematikan kamera.", error));
                }
            });

            function onScanSuccess(decodedText, decodedResult) {
                html5QrcodeScanner.clear();
                let modalInstance = bootstrap.Modal.getInstance(scanModal);
                modalInstance.hide();
                
                const progressBar = document.getElementById('top-progress-bar');
                progressBar.style.opacity = '1';
                progressBar.style.width = '50%';

                window.location.href = decodedText;
            }

            function onScanFailure(error) {}

            const loaders = document.querySelectorAll('.trigger-loader');
            const progressBar = document.getElementById('top-progress-bar');

            loaders.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.href && this.getAttribute('target') !== '_blank' && !this.href.includes('#')) {
                        if(window.innerWidth <= 991) {
                            sidebarMenu.classList.remove('active');
                            sidebarOverlay.classList.remove('active');
                        }
                        progressBar.style.opacity = '1';
                        progressBar.style.width = '35%';
                        setTimeout(() => { progressBar.style.width = '75%'; }, 300);
                    }
                });
            });

            const themeToggleBtn = document.getElementById('darkModeToggle');
            const themeIcon = themeToggleBtn.querySelector('i');
            const bodyElement = document.body;

            /* 🔥 Penyesuaian: Mengganti icon bulan/matahari secara otomatis saat load 🔥 */
            if (bodyElement.classList.contains('dark-mode')) {
                themeIcon.classList.replace('fa-moon', 'fa-sun'); 
            }

            themeToggleBtn.addEventListener('click', function() {
                bodyElement.classList.toggle('dark-mode');
                themeIcon.style.transform = 'rotate(360deg)';
                themeIcon.style.transition = 'transform 0.4s ease';
                setTimeout(() => themeIcon.style.transform = 'none', 400);

                if (bodyElement.classList.contains('dark-mode')) {
                    localStorage.setItem('earsip_theme', 'dark');
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                } else {
                    localStorage.setItem('earsip_theme', 'light');
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>