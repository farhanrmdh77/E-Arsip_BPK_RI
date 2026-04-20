<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | E-Arsip Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700;800&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        /* Ikon Raksasa Transparan di Latar Belakang */
        .error-bg-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 35vw; /* Menyesuaikan ukuran layar */
            color: #C8A35A;
            opacity: 0.03;
            z-index: 1;
            pointer-events: none;
        }

        .error-container {
            text-align: center;
            position: relative;
            z-index: 2;
            padding: 40px;
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .error-code {
            font-size: 150px;
            font-weight: 800;
            background: linear-gradient(135deg, #C8A35A 0%, #fde68a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: -20px;
            line-height: 1;
            text-shadow: 0 10px 30px rgba(200, 163, 90, 0.2);
        }

        .error-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .error-desc {
            font-size: 15px;
            color: #94a3b8;
            max-width: 500px;
            margin: 0 auto 40px auto;
            line-height: 1.6;
        }

        .btn-back {
            background: linear-gradient(135deg, #C8A35A 0%, #ae8b49 100%);
            color: #ffffff;
            padding: 16px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(200, 163, 90, 0.3);
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(200, 163, 90, 0.4);
            color: #ffffff;
        }
    </style>
</head>
<body>

    <i class="fa-solid fa-vault error-bg-icon"></i>

    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Brankas Tidak Ditemukan</h1>
        <p class="error-desc">Maaf, halaman atau dokumen arsip yang Anda tuju tidak tersedia. Dokumen mungkin telah dipindahkan ke tong sampah, dihapus, atau URL yang dimasukkan salah.</p>
        
        <a href="{{ url('/') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Utama
        </a>
    </div>

</body>
</html>