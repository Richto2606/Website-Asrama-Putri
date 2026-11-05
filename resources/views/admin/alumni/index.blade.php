<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Alumni | Asrama Putri Junjung Buyah Kukar YK</title>
    <style>
        :root { --ink: #171717; --muted: #737373; --line: #e7e7e7; --soft: #fafafa; --yellow: #ffe51f; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #fff; color: var(--ink); font-family: Inter, system-ui, sans-serif; }
        a { color: inherit; text-decoration: none; }
        .topbar { height: 61px; display: flex; align-items: center; justify-content: space-between; padding: 0 28px; border-bottom: 1px solid var(--line); }
        .brand { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 800; }
        .brand img { width: 29px; height: 29px; border-radius: 50%; object-fit: cover; }
        .crumb { color: var(--muted); font-weight: 400; }
        .actions { display: flex; gap: 10px; }
        .button { padding: 11px 15px; border: 1px solid var(--line); border-radius: 8px; background: #fff; font-size: 13px; font-weight: 800; }
        .button.yellow { border: 0; background: var(--yellow); }
        main { max-width: 1500px; margin: auto; padding: 32px 28px 60px; }
        h1 { margin: 0; font-size: 26px; letter-spacing: -.04em; }
        .subtitle { margin: 7px 0 24px; color: var(--muted); font-size: 14px; }
        .notice { margin-bottom: 18px; padding: 12px 15px; border-radius: 8px; color: #166534; background: #ecfdf5; font-size: 13px; }
        .panel { overflow: hidden; border: 1px solid var(--line); border-radius: 10px; }
        .panel-head { display: flex; align-items: center; justify-content: space-between; padding: 17px 20px; border-bottom: 1px solid var(--line); }
        .panel-head strong { font-size: 15px; }
        .search { width: 280px; padding: 10px 12px; border: 1px solid var(--line); border-radius: 7px; outline: 0; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; min-width: 950px; border-collapse: collapse; }
        th, td { padding: 14px 17px; border-bottom: 1px solid #eee; text-align: left; vertical-align: middle; }
        th { color: #686868; background: #fcfcfc; font-size: 10px; letter-spacing: .14em; text-transform: uppercase; }
         td { font-size: 13px; }
         .detail-link { display: inline-block; margin-top: 7px; color: #675900; font-size: 11px; font-weight: 800; }
         td strong { display: block; }
        td small { display: block; margin-top: 4px; color: #929292; font-size: 11px; }
        .badge { display: inline-flex; padding: 5px 10px; border: 1px solid #ddd; border-radius: 99px; color: #777; background: #fafafa; font-size: 10px; font-weight: 800; }
        .empty { padding: 45px; color: var(--muted); text-align: center; }
        .pagination { padding: 15px 20px; border-top: 1px solid var(--line); }
        @media (max-width: 700px) { .topbar, .panel-head { align-items: flex-start; gap: 12px; flex-direction: column; height: auto; padding: 18px; } main { padding: 24px 14px 45px; } .actions, .search { width: 100%; } .button { flex: 1; text-align: center; } }
        :root { --ink: #17201d; --muted: #68736f; --line: #e5e9e6; --soft: #f7f9f6; --yellow: #d8ff27; --shadow: 0 14px 40px rgba(23,32,29,.07); }
        body { background: radial-gradient(circle at 90% 0, #e9f7c8 0, transparent 28%), #f4f7f3; }
        .topbar { position: sticky; top: 0; z-index: 4; height: 68px; padding: 0 34px; background: rgba(255,255,255,.9); backdrop-filter: blur(16px); }
        main { max-width: 1560px; padding: 42px 34px 70px; } h1 { font-size: clamp(26px,3vw,34px); } .panel { border-radius: 16px; background: rgba(255,255,255,.82); box-shadow: var(--shadow); } .button { border-radius: 10px; transition: .18s; } .button:hover { transform: translateY(-1px); } th { color: #68736f; background: #fbfcfa; } td { color: #34413c; } tbody tr:hover { background: #f7faed; }
        html[data-theme="dark"] body { color: #f3f7f4; background: #101412; } html[data-theme="dark"] .topbar, html[data-theme="dark"] .panel { color: #f3f7f4; background: rgba(22,29,26,.94); border-color: #35413b; } html[data-theme="dark"] th { color: #dce7e0; background: #26312b; border-color: #405047; } html[data-theme="dark"] td { color: #d7e1da; border-color: #35413b; } html[data-theme="dark"] tbody tr:hover { background: #29362d; } html[data-theme="dark"] h1, html[data-theme="dark"] h2, html[data-theme="dark"] .brand strong { color: #f3f7f4; } html[data-theme="dark"] .search, html[data-theme="dark"] input { color: #f3f7f4; background: #202923; border-color: #4a5a50; }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/logo-siap-kukar.png') }}" alt="Logo Asrama Putri Junjung Buyah Kukar YK"><span>Asrama Putri Junjung Buyah Kukar YK</span><span class="crumb">› Kelola Alumni</span></a>
        <div class="actions"><a class="button" href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    </header>
    <main>
        <h1>Kelola Alumni</h1>
        <p class="subtitle">Data penghuni yang sudah selesai tinggal dan otomatis dipindahkan dari data aktif.</p>
        <section class="panel">
            <div class="panel-head"><strong>Daftar Alumni ({{ $alumnis->total() }})</strong><input class="search" id="search" placeholder="Cari nama, kampus..."></div>
            <div class="table-wrap"><table><thead><tr><th>Nama Alumni</th><th>NIK</th><th>Institusi Pendidikan</th><th>Semester Terakhir</th><th>Kamar Terakhir</th><th>Tanggal Keluar</th><th>Alasan Keluar</th><th>Dipindahkan Pada</th></tr></thead><tbody>
                @forelse ($alumnis as $alumni)
                    <tr data-search="{{ strtolower($alumni->name.' '.$alumni->email.' '.$alumni->institusi_pendidikan) }}"><td><strong>{{ $alumni->name }}</strong><small>{{ $alumni->email }}</small><a class="detail-link" href="{{ route('admin.alumni.show', $alumni) }}">Lihat Detail →</a></td><td>{{ $alumni->nik ?? '-' }}</td><td>{{ $alumni->institusi_pendidikan ?? '-' }}</td><td><span class="badge">SEMESTER {{ $alumni->semester ?? '-' }}</span></td><td>{{ $alumni->nomor_kamar ? 'Kamar '.$alumni->nomor_kamar : '-' }}</td><td>{{ $alumni->tanggal_keluar?->format('d M Y') ?: '-' }}</td><td>{{ $alumni->alasan_keluar ?: '-' }}</td><td>{{ $alumni->bergabung_sebagai_alumni_pada?->format('d M Y') }}</td></tr>
                @empty
                    <tr><td class="empty" colspan="8">Belum ada alumni. Penghuni yang dihapus akan muncul di sini.</td></tr>
                @endforelse
            </tbody></table></div>
            @if ($alumnis->hasPages()) <div class="pagination">{{ $alumnis->links() }}</div> @endif
        </section>
    </main>
    <script>const search=document.querySelector('#search');const rows=[...document.querySelectorAll('tr[data-search]')];search.addEventListener('input',()=>{const q=search.value.toLowerCase().trim();rows.forEach(row=>{row.hidden=!row.dataset.search.includes(q);});});</script>
</body>
</html>
