<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pendaftaran</title>
    <style>
        @page { margin: 28px; }
        body { color: #171717; font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        h1 { margin: 0 0 5px; text-align: center; font-size: 17px; }
        .meta { margin-bottom: 18px; color: #666; text-align: center; }
        table { width: 100%; border-collapse: collapse; page-break-inside: auto; } thead { display: table-header-group; } tr { page-break-inside: avoid; page-break-after: auto; }
        th { color: #fff; background: #17201d; font-weight: bold; }
        th, td { padding: 6px; border: 1px solid #d7d7d7; text-align: left; vertical-align: top; }
        tr:nth-child(even) { background: #f5f5f5; }
        .status { white-space: nowrap; }
    </style>
</head>
<body>
    <h1>DATA PENDAFTARAN Asrama Putri Junjung Buyah Kukar YK</h1>
    <div class="meta">Filter status: {{ $status ?: 'Semua' }} | Kampus: {{ $campus ?: 'Semua' }} | Dicetak: {{ now()->format('d-m-Y H:i') }}</div>
    <table>
        <thead><tr><th>No</th><th>Nomor Pendaftaran</th><th>Nama</th><th>Email</th><th>NIK</th><th>Institusi</th><th>Semester</th><th>Status</th><th>Alasan/Catatan Admin</th><th>Tanggal Daftar</th></tr></thead>
        <tbody>
            @forelse ($pendaftarans as $index => $pendaftaran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pendaftaran->nomor_pendaftaran }}</td>
                    <td>{{ $pendaftaran->nama_lengkap }}</td>
                    <td>{{ $pendaftaran->user?->email }}</td>
                    <td>{{ $pendaftaran->nik }}</td>
                    <td>{{ $pendaftaran->institusi_pendidikan }}</td>
                    <td>{{ $pendaftaran->semester }}</td>
                    <td class="status">{{ ucfirst(str_replace('_', ' ', $pendaftaran->status_pendaftaran)) }}</td>
                    <td>{{ $pendaftaran->catatan_admin ?: '-' }}</td>
                    <td>{{ $pendaftaran->created_at?->format('d-m-Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="10">Belum ada data pendaftaran.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
