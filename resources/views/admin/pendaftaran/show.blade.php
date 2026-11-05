<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pendaftaran | Asrama Putri Junjung Buyah Kukar YK</title>
    <style>
        :root { --ink: #171717; --muted: #737373; --line: #e5e5e5; --soft: #fafafa; --yellow: #ffe51f; --green: #078455; --red: #b42318; }
        * { box-sizing: border-box; }
        body { margin: 0; color: var(--ink); background: #f7f7f5; font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        button, input { font: inherit; }
        .topbar { height: 61px; display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 0 28px; border-bottom: 1px solid var(--line); background: #fff; }
        .brand { display: flex; align-items: center; gap: 10px; color: var(--muted); font-size: 14px; white-space: nowrap; }
        .brand img { width: 29px; height: 29px; border-radius: 50%; object-fit: cover; }
        .brand strong { color: var(--ink); }
        .top-actions { display: flex; align-items: center; gap: 8px; }
        .icon-button, .back { border: 1px solid var(--line); border-radius: 8px; background: #fff; cursor: pointer; }
        .icon-button { width: 38px; height: 38px; color: var(--muted); }
        main { width: min(1100px, calc(100% - 48px)); margin: auto; padding: 34px 0 65px; }
        .breadcrumb { margin-bottom: 18px; color: var(--muted); font-size: 12px; }
        .breadcrumb a { color: var(--muted); text-decoration: none; }
        .breadcrumb strong { color: var(--ink); }
        .top { display: flex; align-items: end; justify-content: space-between; gap: 24px; margin-bottom: 26px; }
        .heading { display: flex; align-items: center; gap: 12px; }
        h1, h2 { margin: 0; }
        h1 { font-size: clamp(25px, 4vw, 34px); letter-spacing: -.05em; }
        .subtitle { margin: 7px 0 0; color: var(--muted); font-size: 13px; }
        .top-actions { flex-wrap: wrap; justify-content: end; }
        .back { display: inline-flex; align-items: center; justify-content: center; padding: 10px 13px; color: var(--ink); font-size: 12px; font-weight: 800; text-decoration: none; }
        .back.primary { border-color: var(--ink); color: #fff; background: var(--ink); }
        .back.accept { border-color: #9ae6c8; color: var(--green); background: #effdf7; }
        .back.reject { border-color: #ffc5c5; color: var(--red); background: #fff5f5; }
        .card { margin-bottom: 18px; padding: 25px; border: 1px solid var(--line); border-radius: 14px; background: #fff; box-shadow: 0 8px 24px #17171708; }
        .card-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 21px; }
        h2 { font-size: 17px; letter-spacing: -.02em; }
        .card-heading span { color: var(--muted); font-size: 11px; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; overflow: hidden; border: 1px solid var(--line); border-radius: 10px; background: var(--line); }
        .item { min-height: 76px; padding: 15px; background: #fff; }
        .item strong, .item span { display: block; }
        .item strong { margin-bottom: 7px; color: var(--muted); font-size: 10px; letter-spacing: .07em; text-transform: uppercase; }
        .item span { overflow-wrap: anywhere; font-size: 13px; font-weight: 650; }
         .files { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
         .file-card { overflow: hidden; border: 1px solid var(--line); border-radius: 9px; background: var(--soft); }
         .file-card strong { display: block; padding: 12px 14px; font-size: 13px; }
         .file-preview { display: block; width: 100%; height: 220px; border: 0; background: #eee; }
         .file-link { display: block; padding: 9px 14px; border-top: 1px solid var(--line); color: var(--green); font-size: 11px; font-weight: 750; }
         .decision { display: grid; gap: 12px; margin-top: 20px; padding: 16px; border: 1px solid var(--line); border-radius: 10px; background: var(--soft); }
         .decision textarea { min-height: 82px; padding: 10px; border: 1px solid var(--line); border-radius: 7px; resize: vertical; font: inherit; }
         .decision-actions { display: flex; flex-wrap: wrap; gap: 8px; }
         .decision-actions button { padding: 9px 12px; border: 1px solid var(--line); border-radius: 7px; background: #fff; cursor: pointer; font-weight: 750; }
         .decision-actions .accept { border-color: #9ae6c8; color: var(--green); }
         .decision-actions .reject { border-color: #ffc5c5; color: var(--red); }

        .status { display: inline-flex !important; width: fit-content; padding: 6px 10px; border-radius: 99px; color: #9a6500; background: #fff8da; font-size: 11px !important; font-weight: 800 !important; text-transform: capitalize; }
        @media (max-width: 750px) { .top { align-items: flex-start; flex-direction: column; } .top-actions { justify-content: start; } .grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 500px) { main { width: min(100% - 28px, 1100px); padding-top: 24px; } .topbar { padding: 0 16px; } .brand span:not(strong) { display: none; } .grid, .files { grid-template-columns: 1fr; } .card { padding: 18px; } }
        html[data-theme="dark"] body { color: #f4f4f4; background: #111; } html[data-theme="dark"] .card { background: #191919; border-color: #333; } html[data-theme="dark"] .back { color: #eee; background: #292929; } html[data-theme="dark"] h1, html[data-theme="dark"] h2, html[data-theme="dark"] .item span { color: #fff; }
        :root { --ink: #17201d; --muted: #68736f; --line: #e5e9e6; --soft: #f7f9f6; --yellow: #d8ff27; }
        body { background: radial-gradient(circle at 90% 0, #e9f7c8 0, transparent 28%), #f4f7f3; } .topbar { position: sticky; top: 0; z-index: 4; height: 68px; padding: 0 34px; background: rgba(255,255,255,.9); backdrop-filter: blur(16px); } main { padding-top: 42px; } .card { border-radius: 16px; box-shadow: 0 14px 40px rgba(23,32,29,.07); } .item, .file-card, .decision { background: #fbfcfa; } html[data-theme="dark"] body { color: #f3f7f4; background: #101412; } html[data-theme="dark"] .topbar, html[data-theme="dark"] .card { background: #1a211e; border-color: #35413b; } html[data-theme="dark"] h1, html[data-theme="dark"] h2, html[data-theme="dark"] .brand strong, html[data-theme="dark"] .item span { color: #f3f7f4; } html[data-theme="dark"] .item, html[data-theme="dark"] .file-card, html[data-theme="dark"] .decision { background: #202923; border-color: #35413b; } html[data-theme="dark"] .item strong, html[data-theme="dark"] .subtitle, html[data-theme="dark"] .card-heading span { color: #aebdb4; } html[data-theme="dark"] .back { color: #f3f7f4; background: #202923; border-color: #4a5a50; }
    </style>
    <script>document.documentElement.dataset.theme = localStorage.getItem('admin-theme') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');</script>
</head>
<body>
    <header class="topbar"><a class="brand" href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/logo-siap-kukar.png') }}" alt="Logo Asrama Putri Junjung Buyah Kukar YK"><span>Asrama Putri Junjung Buyah Kukar YK</span><span>›</span><strong>Detail Pendaftaran</strong></a><div class="top-actions"><button class="icon-button" id="theme-toggle" type="button" aria-label="Ganti mode warna">☼</button></div></header>
    <main>
        <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a><span> › </span><a href="{{ route('admin.pendaftaran.index') }}">Kelola Pendaftaran</a><span> › </span><strong>Detail</strong></div>
        <header class="top">
            <div><div class="heading"><h1>Detail Pendaftaran</h1><span class="status">{{ $pendaftaran->status_pendaftaran }}</span></div><p class="subtitle">Periksa biodata dan berkas calon penghuni sebelum mengambil keputusan.</p></div>
            <div class="top-actions"><a class="back" href="{{ route('admin.pendaftaran.index') }}">Kembali</a>@if ($pendaftaran->status_pendaftaran === 'menunggu')<form method="POST" action="{{ route('admin.pendaftaran.update', $pendaftaran) }}" onsubmit="return confirm('Terima pendaftaran ini?')">@csrf @method('PATCH')<input type="hidden" name="status_pendaftaran" value="diterima"><button class="back accept" type="submit">Terima</button></form><form method="POST" action="{{ route('admin.pendaftaran.update', $pendaftaran) }}" onsubmit="return confirm('Tolak pendaftaran ini?')">@csrf @method('PATCH')<input type="hidden" name="status_pendaftaran" value="ditolak"><button class="back reject" type="submit">Tolak</button></form>@endif</div>
        </header>
        <section class="card">
            <div class="card-heading"><h2>Data Akun dan Biodata</h2><span>Informasi pendaftar</span></div>
            <div class="grid">
                <div class="item"><strong>Nama lengkap</strong><span>{{ $pendaftaran->nama_lengkap }}</span></div>
                <div class="item"><strong>Email</strong><span>{{ $pendaftaran->user?->email ?? '-' }}</span></div>
                <div class="item"><strong>NIK</strong><span>{{ $pendaftaran->nik }}</span></div>
                 <div class="item"><strong>Tanggal lahir</strong><span>{{ $pendaftaran->tanggal_lahir?->format('d-m-Y') ?? '-' }}</span></div>
                 <div class="item"><strong>Tempat lahir</strong><span>{{ $pendaftaran->tempat_lahir ?? '-' }}</span></div>
                 <div class="item"><strong>Agama</strong><span>{{ $pendaftaran->agama ?? '-' }}</span></div>
                 <div class="item"><strong>Asal daerah</strong><span>{{ $pendaftaran->asal_daerah }}</span></div>
                <div class="item"><strong>Alamat asal</strong><span>{{ $pendaftaran->alamat_asal }}</span></div>
                <div class="item"><strong>Institusi pendidikan</strong><span>{{ $pendaftaran->institusi_pendidikan }}</span></div>
                 <div class="item"><strong>Jenjang</strong><span>{{ $pendaftaran->jenjang ?? '-' }}</span></div>
                 <div class="item"><strong>Fakultas</strong><span>{{ $pendaftaran->fakultas ?? '-' }}</span></div>
                 <div class="item"><strong>Program studi</strong><span>{{ $pendaftaran->program_studi ?? '-' }}</span></div>
                 <div class="item"><strong>Nomor induk mahasiswa</strong><span>{{ $pendaftaran->nomor_induk_mahasiswa ?? '-' }}</span></div>
                 <div class="item"><strong>Semester</strong><span>{{ $pendaftaran->semester }}</span></div>
                 <div class="item"><strong>No. HP calon</strong><span>{{ $pendaftaran->no_hp_calon ?? '-' }}</span></div>
                 <div class="item"><strong>No. HP orang tua</strong><span>{{ $pendaftaran->no_hp_orang_tua }}</span></div>
                 <div class="item"><strong>Nama orang tua/wali</strong><span>{{ $pendaftaran->nama_orang_tua }}</span></div>
                 <div class="item"><strong>Pekerjaan orang tua</strong><span>{{ $pendaftaran->pekerjaan_orang_tua ?? '-' }}</span></div>
                 <div class="item"><strong>Kontak darurat</strong><span>{{ $pendaftaran->kontak_darurat ?? '-' }}</span></div>
                 <div class="item"><strong>Catatan kesehatan</strong><span>{{ $pendaftaran->catatan_kesehatan ?? '-' }}</span></div>
             </div>
        </section>
        <section class="card">
            <div class="card-heading"><h2>Berkas Pendaftaran</h2><span>Dokumen pendukung</span></div>
             <div class="files">
                 @forelse ($pendaftaran->berkas as $berkas)
                     <article class="file-card"><strong>{{ str_replace('_', ' ', $berkas->jenis_berkas) }}</strong>@if (in_array(strtolower(pathinfo($berkas->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))<img class="file-preview" src="{{ asset('storage/'.$berkas->file_path) }}" alt="Pratinjau {{ $berkas->jenis_berkas }}">@else<iframe class="file-preview" src="{{ asset('storage/'.$berkas->file_path) }}" title="Pratinjau {{ $berkas->jenis_berkas }}"></iframe>@endif<a class="file-link" href="{{ asset('storage/'.$berkas->file_path) }}" target="_blank" rel="noopener">Buka berkas</a></article>
                 @empty
                     <span>Belum ada berkas.</span>
                 @endforelse
             </div>
             @if ($pendaftaran->status_pendaftaran === 'menunggu')
             <form class="decision" method="POST" action="{{ route('admin.pendaftaran.update', $pendaftaran) }}">
                 @csrf @method('PATCH')
                 <label for="catatan_admin"><strong>Alasan atau pesan untuk pendaftar</strong><textarea id="catatan_admin" name="catatan_admin" required placeholder="Tulis alasan keputusan yang akan tampil di halaman cek status...">{{ old('catatan_admin', $pendaftaran->catatan_admin) }}</textarea></label>
                 <div class="decision-actions"><button class="accept" name="status_pendaftaran" value="diterima" type="submit">Terima</button><button class="reject" name="status_pendaftaran" value="ditolak" type="submit">Tolak</button><button name="status_pendaftaran" value="perlu_perbaikan" type="submit">Minta perbaikan</button><button name="status_pendaftaran" value="daftar_tunggu" type="submit">Masuk daftar tunggu</button></div>
             </form>
             @elseif ($pendaftaran->catatan_admin)
                 <div class="decision"><strong>Pesan admin</strong><span>{{ $pendaftaran->catatan_admin }}</span></div>
             @endif
         </section>
    </main>
<script>const themeToggle=document.querySelector('#theme-toggle');const dark=document.documentElement.dataset.theme==='dark';themeToggle.textContent=dark?'☀':'☾';themeToggle.onclick=()=>{const next=document.documentElement.dataset.theme==='dark'?'light':'dark';document.documentElement.dataset.theme=next;localStorage.setItem('admin-theme',next);themeToggle.textContent=next==='dark'?'☀':'☾';};</script>
</body>
</html>
