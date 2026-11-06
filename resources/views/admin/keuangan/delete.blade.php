<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hapus Data Keuangan | Asrama Putri Junjung Buyah Kukar YK</title>
    <style>
        :root { --ink: #17201d; --muted: #68736f; --line: #e5e9e6; --red: #b42318; }
        * { box-sizing: border-box; }
        body { margin: 0; color: var(--ink); background: #f4f7f3; font-family: Inter, system-ui, sans-serif; }
        a { color: inherit; text-decoration: none; }
        .topbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 34px; border-bottom: 1px solid var(--line); background: #fff; }
        .brand { font-weight: 800; }
        .button { padding: 10px 14px; border: 1px solid var(--line); border-radius: 8px; background: #fff; font-size: 12px; font-weight: 800; }
        main { max-width: 1560px; margin: auto; padding: 42px 34px 70px; }
        h1 { margin: 0; font-size: 32px; }
        .intro { color: var(--muted); font-size: 14px; }
        .notice { margin: 18px 0; padding: 12px 15px; border-radius: 8px; color: #166534; background: #ecfdf5; font-size: 13px; }
        .panel { overflow-x: auto; border: 1px solid var(--line); border-radius: 16px; background: #fff; }
        table { width: 100%; min-width: 950px; border-collapse: collapse; }
        th, td { padding: 14px 18px; border-bottom: 1px solid #edf0ed; text-align: left; font-size: 13px; }
        th { color: var(--muted); background: #fbfcfa; font-size: 10px; letter-spacing: .1em; text-transform: uppercase; }
        .amount { font-weight: 800; white-space: nowrap; }
        .delete { padding: 8px 11px; border: 1px solid #ffc8c8; border-radius: 7px; color: var(--red); background: #fff5f5; font-size: 11px; font-weight: 800; cursor: pointer; }
        .locked { color: var(--muted); font-size: 11px; }
        .empty { padding: 45px; color: var(--muted); text-align: center; }
    </style>
</head>
<body>
    <header class="topbar"><a class="brand" href="{{ route('admin.laporan') }}">Asrama Putri Junjung Buyah Kukar YK › Laporan › Hapus Data</a><a class="button" href="{{ route('admin.laporan') }}">Kembali ke Laporan</a></header>
    <main>
        <h1>Hapus Data Laporan</h1>
        <p class="intro">Hapus transaksi manual satu per satu. Data yang berasal dari pembayaran tidak dapat dihapus dari halaman ini.</p>
        @if (session('success')) <div class="notice">{{ session('success') }}</div> @endif
        <section class="panel"><table><thead><tr><th>Tanggal</th><th>Jenis</th><th>No Transaksi</th><th>Kategori</th><th>Keterangan</th><th>Total</th><th>Aksi</th></tr></thead><tbody>
            @forelse ($transaksi as $item)
                <tr><td>{{ $item->tanggal?->format('d M Y') }}</td><td>{{ ucfirst($item->jenis_transaksi) }}</td><td>{{ $item->pembayaran_id ? 'PAY-'.str_pad($item->pembayaran_id, 5, '0', STR_PAD_LEFT) : 'MAN-'.str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ ucfirst(str_replace('_', ' ', $item->kategori ?? 'lain_lain')) }}</td><td>{{ $item->keterangan }}</td><td class="amount">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td><td>@if ($item->pembayaran_id)<span class="locked">Pembayaran terkunci</span>@else<form method="POST" action="{{ route('admin.keuangan.destroy', $item) }}" onsubmit="return confirm('Hapus transaksi ini?')">@csrf @method('DELETE')<button class="delete" type="submit">Hapus</button></form>@endif</td></tr>
            @empty
                <tr><td class="empty" colspan="7">Belum ada data transaksi.</td></tr>
            @endforelse
        </tbody></table></section>
    </main>
</body>
</html>
