<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ ucfirst($jenis) }}</title>
    <style>
        body { color: #171717; font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { margin: 0 0 6px; text-align: center; font-size: 20px; }
        .period { margin-bottom: 22px; color: #737373; text-align: center; }
        table { width: 100%; border-collapse: collapse; page-break-inside: auto; } thead { display: table-header-group; } tr { page-break-inside: avoid; page-break-after: auto; }
        th { color: #fff; background: #171717; font-weight: bold; }
        th, td { padding: 8px; border: 1px solid #dcdcdc; text-align: left; }
        td.total { text-align: right; white-space: nowrap; }
        .summary { display: flex; justify-content: space-between; margin-top: 18px; padding: 14px; border: 1px solid #dcdcdc; border-radius: 8px; font-size: 13px; }
    </style>
</head>
<body>
    <div style="text-align:center;border-bottom:2px solid #171717;padding-bottom:10px;margin-bottom:14px"><strong style="font-size:17px">ASRAMA PUTRI JUNJUNG BUYAH</strong><br><span>KUTAI KARTANEGARA YOGYAKARTA</span><br><small>Jl. A.M. Sangaji No. 201, Yogyakarta</small></div><h1>LAPORAN {{ strtoupper($jenis) }}</h1>
    <div class="period">Periode: {{ $mulai ?: 'Semua' }} s/d {{ $sampai ?: 'Sekarang' }}</div>
    <table>
        <thead><tr><th>Tanggal</th><th>No Pembayaran</th><th>Penghuni</th><th>Kamar</th><th>Metode</th><th>Kategori</th><th>Keterangan</th>@if ($jenis === 'cashflow')<th>Jenis Arus</th>@endif<th>Total</th></tr></thead>
        <tbody>
            @forelse ($transaksi as $item)
                <tr>
                    <td>{{ $item->tanggal?->format('d M Y') }}</td>
                    <td>{{ $item->pembayaran_id ? 'PAY-'.str_pad($item->pembayaran_id, 5, '0', STR_PAD_LEFT) : 'MAN-'.str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->jenis_transaksi === 'pengeluaran' ? 'Bendahara' : ($item->pembayaran?->user?->name ?? '-') }}</td>
                    <td>{{ $item->pembayaran?->user?->kamar?->nomor_kamar ? 'Kamar '.$item->pembayaran->user->kamar->nomor_kamar : ($item->pembayaran_id ? 'Belum Dialokasikan' : 'Transaksi Manual') }}</td>
                    <td>{{ $item->pembayaran?->metode_pembayaran === 'cash' ? 'Cash' : ($item->pembayaran_id ? 'Transfer' : 'Manual') }}</td>
                    <td>{{ $item->kategori ?? 'lain_lain' }}</td><td>{{ $item->keterangan }}</td>
                    @if ($jenis === 'cashflow')<td>{{ ucfirst($item->jenis_transaksi) }}</td>@endif
                    <td class="total">{{ $jenis === 'cashflow' && $item->jenis_transaksi === 'pengeluaran' ? '-' : '+' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="{{ $jenis === 'cashflow' ? 9 : 8 }}">Belum ada transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="summary">@if ($jenis === 'cashflow')<strong>Pemasukan: Rp {{ number_format($pemasukan, 0, ',', '.') }}</strong><strong>Pengeluaran: Rp {{ number_format($pengeluaran, 0, ',', '.') }}</strong><strong>Saldo: Rp {{ number_format($pemasukan - $pengeluaran, 0, ',', '.') }}</strong>@else<strong>Total {{ ucfirst($jenis) }}:</strong><strong>Rp {{ number_format($jenis === 'pengeluaran' ? $pengeluaran : $pemasukan, 0, ',', '.') }}</strong>@endif</div><div style="width:220px;margin:28px 0 0 auto;text-align:center">Yogyakarta, {{ now()->format('d F Y') }}<br>Pengurus Asrama<div style="margin-top:42px;border-bottom:1px solid #171717"></div>(__________________)</div>
</body>
</html>
