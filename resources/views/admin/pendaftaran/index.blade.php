<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Pendaftaran | Asrama Putri Junjung Buyah Kukar YK</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-siap-kukar.png') }}">
    <style>
        :root { --ink: #171717; --muted: #777; --line: #e5e5e5; --yellow: #ffe51f; --green: #078455; --red: #c53030; }
        * { box-sizing: border-box; }
        body { margin: 0; color: var(--ink); background: #fff; font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        .topbar { height: 61px; display: flex; align-items: center; justify-content: space-between; gap: 24px; padding: 0 25px; border-bottom: 1px solid var(--line); }
        .brand { display: flex; align-items: center; gap: 10px; color: var(--muted); font-size: 14px; white-space: nowrap; }
        .brand img { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; }
        .brand strong { color: var(--ink); }
        .top-search, .search { display: flex; align-items: center; gap: 10px; border: 1px solid var(--line); border-radius: 8px; color: #999; background: #fff; }
        .top-search { width: min(385px, 34vw); padding: 9px 12px; }
        .top-search input, .search input { width: 100%; border: 0; outline: 0; color: var(--ink); background: transparent; font-size: 13px; }
        .shortcut { padding: 3px 7px; border: 1px solid var(--line); border-radius: 4px; font-size: 10px; white-space: nowrap; }
        .top-actions { display: flex; align-items: center; gap: 8px; }
        .back-dashboard { padding: 9px 13px; border: 1px solid var(--line); border-radius: 8px; color: var(--ink); background: #fff; font-size: 12px; font-weight: 700; }
        .icon-button { width: 39px; height: 39px; border: 1px solid var(--line); border-radius: 8px; color: var(--muted); background: #fff; }
        main { max-width: 1650px; margin: auto; padding: 29px 24px 60px; }
        h1 { margin: 0; font-size: 25px; letter-spacing: -.04em; }
        .intro { margin: 6px 0 25px; color: var(--muted); font-size: 14px; }
        .notice { margin-bottom: 18px; padding: 12px 15px; border: 1px solid #bce8d4; border-radius: 8px; color: #166534; background: #effdf7; font-size: 13px; }
         .tabs { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; }
         .tab { padding: 9px 16px; border: 1px solid var(--line); border-radius: 20px; color: #555; font-size: 13px; font-weight: 700; }
         .tab.export-history { margin-left: auto; border-color: var(--green); color: var(--green); }
         .tab.export-pdf { border-color: var(--red); color: var(--red); }
        .tab.active { border-color: var(--ink); color: #fff; background: var(--ink); }
        .panel { overflow: hidden; border: 1px solid var(--line); border-radius: 7px; }
         .panel-head { display: flex; justify-content: space-between; gap: 12px; padding: 16px; border-bottom: 1px solid #f0f0f0; }
         .filters { display: flex; flex-wrap: wrap; gap: 8px; }
         .filters select, .export { padding: 10px 12px; border: 1px solid var(--line); border-radius: 8px; color: var(--ink); background: #fff; font-size: 13px; }
         .export { border-color: var(--green); color: var(--green); font-weight: 700; }
         .search { width: 290px; padding: 10px 12px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; min-width: 1040px; border-collapse: collapse; }
        th, td { padding: 14px 20px; border-bottom: 1px solid #eeeeee; text-align: left; vertical-align: middle; }
        th { color: #626262; background: #fcfcfc; font-size: 10px; letter-spacing: .13em; text-transform: uppercase; white-space: nowrap; }
        td { color: #555; font-size: 13px; }
        tbody tr:hover { background: #fffef3; }
        tbody tr:last-child td { border-bottom: 0; }
        .number { width: 58px; color: #777; font-size: 12px; }
        .applicant { min-width: 190px; color: var(--ink); font-weight: 700; }
        .applicant small, .room small { display: block; margin-top: 4px; color: #999; font-size: 11px; font-weight: 400; }
        .room { min-width: 180px; }
        .badge { display: inline-flex; padding: 5px 10px; border: 1px solid; border-radius: 99px; font-size: 10px; font-weight: 800; text-transform: uppercase; white-space: nowrap; }
        .badge.pending { border-color: #ffd36b; color: #ad6500; background: #fffaf0; }
        .badge.accepted { border-color: #a5e8cc; color: var(--green); background: #effdf7; }
        .badge.rejected { border-color: #ffc1c1; color: var(--red); background: #fff5f5; }
        .actions { display: flex; align-items: center; justify-content: end; gap: 8px; min-width: 250px; }
        .action { padding: 8px 12px; border: 1px solid var(--line); border-radius: 7px; color: #555; background: #fff; font-size: 12px; cursor: pointer; }
        .action.accept { border-color: #9ae6c8; color: var(--green); }
        .action.reject { border-color: #ffbaba; color: var(--red); }
        .action:hover { background: #fafafa; }
        .empty { padding: 48px; color: var(--muted); text-align: center; }
        .pagination { padding: 15px 20px; border-top: 1px solid var(--line); }
        @media (max-width: 800px) { .top-search { display: none; } .topbar { padding: 0 16px; } main { padding: 25px 14px 45px; } .panel-head { justify-content: stretch; } .search { width: 100%; } }
        html[data-theme="dark"] body { color: #f4f4f4; background: #111; } html[data-theme="dark"] .topbar, html[data-theme="dark"] .panel { background: #191919; border-color: #333; } html[data-theme="dark"] .top-search, html[data-theme="dark"] .search, html[data-theme="dark"] .icon-button, html[data-theme="dark"] .action { color: #eee; background: #191919; border-color: #444; } html[data-theme="dark"] input { color: #eee; background: #222; border-color: #444; } html[data-theme="dark"] th { background: #202020; } html[data-theme="dark"] td, html[data-theme="dark"] th { border-color: #333; } html[data-theme="dark"] .brand strong, html[data-theme="dark"] h1 { color: #fff; }
        :root { --ink: #17201d; --muted: #68736f; --line: #e5e9e6; --soft: #f7f9f6; }
        body { background: radial-gradient(circle at 90% 0, #e9f7c8 0, transparent 28%), #f4f7f3; } .topbar { position: sticky; top: 0; z-index: 4; height: 68px; padding: 0 34px; background: rgba(255,255,255,.9); backdrop-filter: blur(16px); } main { max-width: 1560px; padding: 42px 34px 70px; } .panel { border-radius: 16px; background: rgba(255,255,255,.82); box-shadow: 0 14px 40px rgba(23,32,29,.07); } th { background: #fbfcfa; color: #68736f; } td { color: #34413c; } tbody tr:hover { background: #f7faed; }
        html[data-theme="dark"] body { color: #f3f7f4; background: #101412; } html[data-theme="dark"] .topbar, html[data-theme="dark"] .panel { color: #f3f7f4; background: #1a211e; border-color: #35413b; } html[data-theme="dark"] th { color: #dce7e0; background: #26312b; border-color: #405047; } html[data-theme="dark"] td { color: #d7e1da; border-color: #35413b; } html[data-theme="dark"] tbody tr:hover { background: #29362d; } html[data-theme="dark"] h1, html[data-theme="dark"] h2, html[data-theme="dark"] .brand strong { color: #f3f7f4; } html[data-theme="dark"] input, html[data-theme="dark"] select, html[data-theme="dark"] .search, html[data-theme="dark"] .action { color: #f3f7f4; background: #202923; border-color: #4a5a50; }
    </style>
    <script>document.documentElement.dataset.theme = localStorage.getItem('admin-theme') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');</script>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/logo-siap-kukar.png') }}" alt="Logo Asrama Putri Junjung Buyah Kukar YK"><span>Asrama Putri Junjung Buyah Kukar YK</span><span>›</span><strong>Kelola Pendaftaran</strong></a>
        <div class="top-search"><span>⌕</span><input id="global-search" placeholder="Cari penghuni, kamar, pendaftaran..."><span class="shortcut">Ctrl K</span></div>
        <div class="top-actions"><a class="back-dashboard" href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a><button class="icon-button" type="button" aria-label="Notifikasi">♧</button><button class="icon-button" type="button" id="theme-toggle" aria-label="Ganti mode warna">☼</button></div>
    </header>
    <main>
        <h1>Kelola Pendaftaran</h1>
        <p class="intro">Kelola pendaftaran calon penghuni Asrama Putri Kutai Kartanegara.</p>
         <nav class="tabs"><a class="tab {{ $status === 'pending' ? 'active' : '' }}" href="{{ route('admin.pendaftaran.index', ['status' => 'pending']) }}">Menunggu ({{ $pendingCount }})</a><a class="tab {{ $status === 'riwayat' ? 'active' : '' }}" href="{{ route('admin.pendaftaran.index', ['status' => 'riwayat']) }}">Riwayat ({{ $historyCount }})</a><a class="tab export-history" href="{{ route('admin.pendaftaran.export', ['status' => $status, 'campus' => $campus]) }}">Ekspor Excel</a><a class="tab export-pdf" href="{{ route('admin.pendaftaran.export.pdf', ['status' => $status, 'campus' => $campus]) }}">Ekspor PDF</a></nav>
        @if (session('success')) <div class="notice">{{ session('success') }}</div> @endif
         <section class="panel">
             <div class="panel-head"><form class="filters" method="GET" action="{{ route('admin.pendaftaran.index') }}"><input type="hidden" name="search" value="{{ $search }}"><select name="status" onchange="this.form.submit()"><option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua status</option><option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu</option><option value="diterima" {{ $status === 'diterima' ? 'selected' : '' }}>Diterima</option><option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option><option value="perlu_perbaikan" {{ $status === 'perlu_perbaikan' ? 'selected' : '' }}>Perlu perbaikan</option><option value="daftar_tunggu" {{ $status === 'daftar_tunggu' ? 'selected' : '' }}>Daftar tunggu</option></select><select name="campus" onchange="this.form.submit()"><option value="">Semua kampus</option>@foreach ($campuses as $campusOption)<option value="{{ $campusOption }}" {{ $campus === $campusOption ? 'selected' : '' }}>{{ $campusOption }}</option>@endforeach</select><button class="export" type="submit" formaction="{{ route('admin.pendaftaran.export') }}">Ekspor Excel</button></form><form class="search" method="GET" action="{{ route('admin.pendaftaran.index') }}"><input type="hidden" name="status" value="{{ $status }}"><input type="hidden" name="campus" value="{{ $campus }}"><span>⌕</span><input name="search" value="{{ $search }}" placeholder="Cari nama, daerah, atau kampus..."></form></div>
            <div class="table-wrap"><table><thead><tr><th class="number">No</th><th>Nama Pendaftar</th><th>Asal Daerah</th><th>Institusi Pendidikan</th><th>Semester</th><th>Status</th><th>Tanggal Daftar</th><th>Aksi</th></tr></thead><tbody>
                @forelse ($pendaftarans as $index => $pendaftaran)
                    <tr><td class="number">{{ $pendaftarans->firstItem() + $index }}</td><td class="applicant">{{ $pendaftaran->nama_lengkap }}</td><td>{{ $pendaftaran->asal_daerah }}</td><td class="room">{{ $pendaftaran->institusi_pendidikan }}</td><td>Semester {{ $pendaftaran->semester ?? '-' }}</td><td><span class="badge {{ $pendaftaran->status_pendaftaran === 'menunggu' ? 'pending' : ($pendaftaran->status_pendaftaran === 'diterima' ? 'accepted' : 'rejected') }}">{{ $pendaftaran->status_pendaftaran === 'diterima' ? 'Diterima' : ucfirst($pendaftaran->status_pendaftaran) }}</span></td><td>{{ $pendaftaran->created_at?->format('d M Y') }}</td><td><div class="actions"><a class="action" href="{{ route('admin.pendaftaran.show', $pendaftaran) }}">Detail dan keputusan</a></div></td></tr>
                @empty
                    <tr><td colspan="8" class="empty">Belum ada pendaftaran pada kategori ini.</td></tr>
                @endforelse
            </tbody></table></div>
            @if ($pendaftarans->hasPages()) <div class="pagination">{{ $pendaftarans->links() }}</div> @endif
        </section>
    </main>
    <script>
        const globalSearch = document.querySelector('#global-search');
        const formSearch = document.querySelector('input[name="search"]');
        globalSearch.addEventListener('input', () => { formSearch.value = globalSearch.value; });
        document.addEventListener('keydown', (event) => { if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') { event.preventDefault(); globalSearch.focus(); } });
    </script>
<script>const themeToggle=document.querySelector('#theme-toggle');const setTheme=()=>{const dark=document.documentElement.dataset.theme==='dark';themeToggle.textContent=dark?'☀':'☾';themeToggle.onclick=()=>{const next=document.documentElement.dataset.theme==='dark'?'light':'dark';document.documentElement.dataset.theme=next;localStorage.setItem('admin-theme',next);themeToggle.textContent=next==='dark'?'☀':'☾';};};setTheme();</script></body>
</html>
