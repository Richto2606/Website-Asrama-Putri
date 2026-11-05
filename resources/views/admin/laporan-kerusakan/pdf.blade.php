<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Laporan Kerusakan Asrama</title>
    <style>
        @page { margin: 28px; } body { color: #171717; font-family: DejaVu Sans, sans-serif; font-size: 10px; } h1 { margin: 0 0 5px; text-align: center; font-size: 20px; } .period { margin-bottom: 20px; color: #737373; text-align: center; } .report { margin-bottom: 18px; padding: 12px; border: 1px solid #d9d9d9; page-break-inside: avoid; } .report-header { margin-bottom: 9px; padding-bottom: 7px; border-bottom: 1px solid #e5e5e5; } .title { font-size: 14px; font-weight: bold; } .meta { margin-top: 4px; color: #737373; font-size: 9px; } .image { width: 210px; height: 145px; object-fit: cover; } .description { padding-left: 14px; vertical-align: top; line-height: 1.55; } .empty { padding: 20px; border: 1px solid #ddd; text-align: center; }
    </style>
</head>
<body>
    <h1>LAPORAN KERUSAKAN ASRAMA</h1>
    <div class="period">Asrama Putri Junjung Buyah Kukar YK · Dicetak {{ now()->format('d M Y H:i') }}</div>
    @forelse ($laporan as $item)
        <div class="report"><div class="report-header"><div class="title">{{ $item->judul }}</div><div class="meta">Dilaporkan {{ $item->created_at?->format('d M Y H:i') }} · Oleh {{ $item->creator?->name ?? 'Admin' }}</div></div><table width="100%"><tr><td width="230"><img class="image" src="{{ public_path('storage/'.$item->gambar) }}"></td><td class="description"><strong>Deskripsi Kerusakan</strong><br>{{ $item->deskripsi }}</td></tr></table></div>
    @empty
        <div class="empty">Belum ada laporan kerusakan.</div>
    @endforelse
</body>
</html>
