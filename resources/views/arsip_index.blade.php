<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar E-Arsip Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">Daftar Arsip Digital</h2>
            <a href="/import-arsip" class="btn btn-success">+ Import Data Baru</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Berkas</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Warna</th>
                            <th>Tahun</th>
                            <th>Kode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semuaArsip as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_berkas }}</td>
                            <td>{{ $item->deskripsi_berkas }}</td>
                            <td>{{ $item->jumlah_berkas }}</td>
                            <td><span class="badge bg-secondary">{{ $item->warna_berkas }}</span></td>
                            <td>{{ $item->tahun_berkas }}</td>
                            <td><strong>{{ $item->kode_arsip }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data arsip.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>