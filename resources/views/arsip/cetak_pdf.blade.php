<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Isi Folder {{ $kode }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>DAFTAR ISI ARSIP DOKUMEN</h2>
        <p>Kode Folder: <strong>{{ $kode }}</strong> | Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Berkas</th>
                <th width="35%">Deskripsi Berkas</th>
                <th width="10%">Tahun</th>
                <th width="10%">Jml</th>
                <th width="10%">Status Fisik</th>
            </tr>
        </thead>
        <tbody>
            @forelse($arsips as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->nama_berkas }}</td>
                <td>{{ $item->deskripsi_berkas ?: '-' }}</td>
                <td class="text-center">{{ $item->tahun_berkas }}</td>
                <td class="text-center">{{ $item->jumlah_berkas }}</td>
                <td class="text-center">
                    {{ $item->file_dokumen ? 'Ada' : 'Tidak Ada' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada dokumen di dalam folder ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistem E-Arsip Digital - Digenerate otomatis oleh sistem.</p>
    </div>

</body>
</html>