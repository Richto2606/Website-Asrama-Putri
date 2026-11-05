<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Penghuni | Asrama Putri Junjung Buyah Kukar YK</title>
    <style>
         :root { --ink: #17201d; --muted: #68736f; --line: #e5e9e6; --soft: #f7f9f6; --yellow: #d8ff27; --green: #087f5b; --blue: #0875c1; --danger: #b42318; --shadow: 0 14px 40px rgba(23, 32, 29, .07); }
         * { box-sizing: border-box; }
         body { margin: 0; background: radial-gradient(circle at 90% 0, #e9f7c8 0, transparent 28%), #f4f7f3; color: var(--ink); font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: inherit; text-decoration: none; }
        button, input, select { font: inherit; }
         .topbar { position: sticky; top: 0; z-index: 4; height: 68px; display: flex; align-items: center; justify-content: space-between; gap: 24px; padding: 0 34px; border-bottom: 1px solid var(--line); background: rgba(255, 255, 255, .88); backdrop-filter: blur(16px); }
         .brand { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 800; white-space: nowrap; }
         .brand img { box-shadow: 0 4px 12px #17201d1c; }
        .brand img { width: 29px; height: 29px; border-radius: 50%; object-fit: cover; }
        .crumb { display: flex; align-items: center; gap: 12px; color: var(--muted); }
        .crumb strong { color: var(--ink); }
        .top-search, .search { display: flex; align-items: center; gap: 10px; border: 1px solid var(--line); border-radius: 8px; color: #969696; background: #fff; }
        .top-search { width: min(385px, 34vw); padding: 10px 13px; }
        .top-search input, .search input { width: 100%; border: 0; outline: 0; color: var(--ink); background: transparent; font-size: 13px; }
        .shortcut { padding: 3px 7px; border: 1px solid var(--line); border-radius: 4px; font-size: 10px; white-space: nowrap; }
        .top-actions { display: flex; align-items: center; gap: 8px; }
        .icon-button { width: 39px; height: 39px; border: 1px solid var(--line); border-radius: 8px; background: #fff; color: var(--muted); cursor: pointer; }
         main { max-width: 1560px; margin: auto; padding: 42px 34px 70px; }
         .page-head { display: flex; align-items: end; justify-content: space-between; gap: 24px; margin-bottom: 30px; }
         h1 { margin: 0; font-size: clamp(26px, 3vw, 34px); letter-spacing: -.055em; }
         .page-head p { max-width: 520px; line-height: 1.55; }
        .page-head p { margin: 6px 0 0; color: var(--muted); font-size: 14px; }
         .head-actions { display: flex; flex-wrap: wrap; justify-content: end; gap: 8px; }
         .button { padding: 11px 15px; border: 1px solid var(--ink); border-radius: 10px; color: #fff; background: var(--ink); font-size: 12px; font-weight: 800; cursor: pointer; transition: .18s ease; }
         .button:hover { transform: translateY(-1px); box-shadow: 0 7px 18px #17201d1a; }
         .button.light { border-color: var(--line); color: var(--ink); background: #fff; }
         .button.yellow { border-color: var(--yellow); color: var(--ink); background: var(--yellow); }
        .notice, .errors { margin-bottom: 16px; padding: 12px 15px; border-radius: 8px; font-size: 13px; }
        .notice { color: #166534; background: #ecfdf5; }
        .errors { color: #991b1b; background: #fef2f2; }
        .errors ul { margin: 0; padding-left: 18px; }
        .add-panel { display: none; margin-bottom: 20px; padding: 22px; border: 1px solid var(--line); border-radius: 12px; background: var(--soft); }
        .add-panel.open { display: block; }
        .add-panel h2 { margin: 0 0 18px; font-size: 17px; }
        .form-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
        .field { display: grid; gap: 6px; }
        .field label { font-size: 11px; font-weight: 800; }
        .field input, .field select, .filter { width: 100%; padding: 10px 11px; border: 1px solid #ddd; border-radius: 7px; outline: 0; background: #fff; font-size: 13px; }
        .field input:focus, .field select:focus, .search:focus-within { border-color: #aaa; }
        .form-footer { display: flex; justify-content: end; gap: 8px; margin-top: 18px; }
         .panel { overflow: hidden; border: 1px solid var(--line); border-radius: 16px; background: rgba(255, 255, 255, .82); box-shadow: var(--shadow); }
         .panel-head { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 18px 20px; border-bottom: 1px solid var(--line); }
         .tabs { display: flex; gap: 4px; padding: 4px; border: 1px solid var(--line); border-radius: 10px; background: var(--soft); }
         .tab { padding: 9px 15px; border: 0; border-radius: 7px; color: var(--muted); background: transparent; font-size: 12px; cursor: pointer; }
         .tab.active { color: #fff; background: var(--ink); font-weight: 800; box-shadow: 0 4px 10px #17201d22; }
         .search { width: 300px; padding: 11px 13px; }
         .filters { display: flex; gap: 8px; padding: 14px 20px; border-bottom: 1px solid var(--line); background: #fbfcfa; }
        .filter { width: auto; min-width: 145px; color: #5f5f5f; }
        .table-wrap { overflow-x: auto; }
         table { width: 100%; min-width: 1180px; border-collapse: collapse; }
         th, td { padding: 15px 18px; border-bottom: 1px solid #edf0ed; text-align: left; vertical-align: middle; }
         th { color: #76817d; background: #fbfcfa; font-size: 10px; letter-spacing: .14em; text-transform: uppercase; white-space: nowrap; }
         td { color: #44504b; font-size: 13px; }
         tbody tr:hover { background: #f7faed; }
         tbody tr:last-child td { border-bottom: 0; }
        tbody tr:last-child td { border-bottom: 0; }
        .number { width: 58px; color: #8d8d8d; font-size: 12px; text-align: center; }
        .person { min-width: 205px; }
        .person strong { display: block; font-size: 13px; }
         .person small, td small { display: block; margin-top: 4px; color: #929292; font-size: 11px; } .documents { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 7px; } .documents a { color: #675900; font-size: 10px; font-weight: 800; }
        .room strong { display: block; font-size: 13px; }
        .room small { color: #929292; }
        .badge { display: inline-flex; padding: 5px 10px; border: 1px solid; border-radius: 99px; font-size: 10px; font-weight: 800; letter-spacing: .05em; white-space: nowrap; }
        .badge.blue { border-color: #a8dcff; color: #006bb2; background: #eef9ff; }
        .badge.green { border-color: #9ae6c8; color: #078455; background: #effdf7; }
        .badge.gray { border-color: #ddd; color: #777; background: #fafafa; }
         .actions-cell { min-width: 260px; }
         .room-form { display: flex; align-items: center; gap: 6px; }
         .room-form select { max-width: 145px; padding: 8px; border: 1px solid var(--line); border-radius: 7px; font-size: 11px; }
         .save { padding: 8px 10px; border: 1px solid #9ae6c8; border-radius: 7px; color: var(--green); background: #f1fff9; font-size: 11px; font-weight: 800; cursor: pointer; }
         .detail { display: inline-block; margin: 7px 0 0; padding: 8px 10px; border: 1px solid #eadb62; border-radius: 7px; color: #675900; background: #fff8c9; font-size: 11px; font-weight: 800; }
         .status-form { padding-top: 7px; margin-top: 7px; border-top: 1px solid var(--line); }
         .status-form select, .status-form input { max-width: 145px; padding: 7px; border: 1px solid var(--line); border-radius: 7px; font-size: 11px; }
         .delete { margin-top: 7px; padding: 0; border: 0; color: #b42318; background: none; font-size: 11px; cursor: pointer; }
         .status-form { display: grid; gap: 5px; margin-top: 7px; }
         .status-form select, .status-form input { max-width: 145px; padding: 7px; border: 1px solid var(--line); border-radius: 7px; font-size: 11px; }
        .empty { padding: 45px; color: var(--muted); text-align: center; }
        .pagination { padding: 15px 20px; border-top: 1px solid var(--line); }
        @media (max-width: 900px) { .top-search { display: none; } .page-head { align-items: flex-start; flex-direction: column; } .form-grid { grid-template-columns: repeat(2, 1fr); } .panel-head { align-items: flex-start; flex-direction: column; } .search { width: 100%; } }
        @media (max-width: 560px) { .topbar { padding: 0 16px; } .crumb span { display: none; } main { padding: 24px 14px 45px; } .head-actions { width: 100%; } .head-actions .button { flex: 1; } .form-grid { grid-template-columns: 1fr; } .filters { flex-wrap: wrap; padding: 12px; } .filter { flex: 1; min-width: 130px; } }
         .topbar, .panel, .add-panel { color: #17201d; }
         .panel-head, .filters, th { color: #4d5a55; }
         .field label, .person strong, .room strong, td { color: #34413c; }
         .field input, .field select, .filter, .room-form select, .status-form select, .status-form input, .search, .top-search { color: #17201d; background: #fff; }
         html[data-theme="dark"] body { color: #f3f7f4; background: #101412; }
         html[data-theme="dark"] .topbar { color: #f3f7f4; background: rgba(22, 29, 26, .94); border-color: #35413b; }
         html[data-theme="dark"] .panel, html[data-theme="dark"] .add-panel { color: #f3f7f4; background: #1a211e; border-color: #35413b; }
         html[data-theme="dark"] .panel-head, html[data-theme="dark"] .filters { color: #dce7e0; background: #202923; border-color: #35413b; }
         html[data-theme="dark"] th { color: #dce7e0; background: #26312b; border-color: #405047; }
         html[data-theme="dark"] td { color: #d7e1da; border-color: #35413b; }
         html[data-theme="dark"] tbody tr:hover { background: #29362d; }
         html[data-theme="dark"] .field label, html[data-theme="dark"] .person strong, html[data-theme="dark"] .room strong, html[data-theme="dark"] h1, html[data-theme="dark"] h2, html[data-theme="dark"] h3, html[data-theme="dark"] .brand, html[data-theme="dark"] .brand strong { color: #f3f7f4; }
         html[data-theme="dark"] .page-head p, html[data-theme="dark"] .person small, html[data-theme="dark"] td small, html[data-theme="dark"] .crumb, html[data-theme="dark"] .muted { color: #aebdb4; }
         html[data-theme="dark"] .top-search, html[data-theme="dark"] .search, html[data-theme="dark"] .icon-button, html[data-theme="dark"] .button.light, html[data-theme="dark"] .filter, html[data-theme="dark"] .room-form select, html[data-theme="dark"] .field input, html[data-theme="dark"] .field select, html[data-theme="dark"] .status-form select, html[data-theme="dark"] .status-form input { color: #f3f7f4; background: #202923; border-color: #4a5a50; }
         html[data-theme="dark"] input::placeholder { color: #9cac9f; }
         html[data-theme="dark"] .tab { color: #b7c5bc; }
         html[data-theme="dark"] .tab.active { color: #17201d; background: #d8ff27; }
         html[data-theme="dark"] .button { color: #17201d; background: #d8ff27; border-color: #d8ff27; }
         html[data-theme="dark"] .button.light { color: #f3f7f4; background: #202923; }
         html[data-theme="dark"] .notice { color: #b9f5d6; background: #123326; }
         html[data-theme="dark"] .errors { color: #ffc4c4; background: #3a1d1d; }
         html[data-theme="dark"] .detail { color: #17201d; background: #ffe96b; }
         html[data-theme="dark"] .save { color: #b9f5d6; background: #123326; border-color: #3b9d74; }
         html[data-theme="dark"] .status-form { border-color: #35413b; }
    </style>
    <script>document.documentElement.dataset.theme = localStorage.getItem('admin-theme') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');</script>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/logo-siap-kukar.png') }}" alt="Logo Asrama Putri Junjung Buyah Kukar YK"><span>Asrama Putri Junjung Buyah Kukar YK</span><span class="crumb">› <strong>Kelola Penghuni</strong></span></a>
        <div class="top-search"><span>⌕</span><input id="global-search" placeholder="Cari penghuni, kamar, kampus..."><span class="shortcut">Ctrl K</span></div>
        <div class="top-actions"><button class="icon-button" type="button" aria-label="Notifikasi">♧</button><button class="icon-button" type="button" id="theme-toggle" aria-label="Ganti mode warna">☼</button></div>
    </header>
    <main>
        <div class="page-head">
            <div><h1>Kelola Penghuni</h1><p>Kelola data penghuni aktif dan penempatan kamar dalam satu halaman.</p></div>
             <div class="head-actions"><a class="button light" href="{{ route('admin.dashboard') }}">Dashboard</a><a class="button light" href="{{ route('admin.penghuni.export') }}">Export Excel</a><a class="button light" href="{{ route('admin.penghuni.export.pdf') }}">Export PDF</a><button class="button light" id="import-button" type="button">Impor Excel</button><button class="button" id="add-button" type="button">＋ Tambah Penghuni</button></div>
        </div>
        @if (session('success')) <div class="notice">{{ session('success') }}</div> @endif
        @if ($errors->any()) <div class="errors"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
        @if ($expiringResidents->isNotEmpty()) <div class="notice">Pengingat: {{ $expiringResidents->count() }} penghuni akan mengakhiri masa tinggal dalam 30 hari.</div> @endif
        <section class="add-panel" id="import-panel"><h2>Impor Data Penghuni</h2><p>Gunakan file CSV atau Excel dengan kolom: Nama, Email, NIK, Institusi Pendidikan, Semester, ID Asrama, Tanggal Masuk, Jenjang.</p><form method="POST" action="{{ route('admin.penghuni.import') }}" enctype="multipart/form-data"><input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="field"><label>File Excel/CSV</label><input type="file" name="file" accept=".csv,.txt,.xlsx" required></div><div class="form-footer"><button class="button light" id="cancel-import" type="button">Batal</button><button class="button" type="submit">Impor Data</button></div></form></section>
        <section class="add-panel" id="add-panel">
            <h2>Tambah Penghuni</h2>
            <form method="POST" action="{{ route('admin.penghuni.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="field"><label>Nama Lengkap</label><input name="name" value="{{ old('name') }}" required></div>
                    <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                    <div class="field"><label>NIK</label><input name="nik" maxlength="16" required></div>
                    <div class="field"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" required></div>
                    <div class="field"><label>Asal Daerah</label><input name="asal_daerah" value="Kutai Kartanegara" readonly required></div>
                    <div class="field"><label>Alamat Asal</label><input name="alamat_asal" required></div>
                    <div class="field"><label>Institusi Pendidikan</label><input name="institusi_pendidikan" required></div>
                    <div class="field"><label>Jenjang</label><input name="jenjang" placeholder="S1 / S2"></div>
                    <div class="field"><label>Semester</label><input type="number" name="semester" min="1" max="14" required></div>
                    <div class="field"><label>ID Asrama</label><input name="id_asrama"></div>
                    <div class="field"><label>Tanggal Masuk</label><input type="date" name="tanggal_masuk" value="{{ now()->format('Y-m-d') }}"></div>
                    <div class="field"><label>No. HP Orang Tua</label><input name="no_hp_orang_tua" required></div>
                    <div class="field"><label>Nama Orang Tua/Wali</label><input name="nama_orang_tua" required></div>
                    <div class="field"><label>Alokasi Kamar</label><select name="kamar_id"><option value="">Belum Dialokasikan</option>@foreach ($kamars as $kamar)<option value="{{ $kamar->id }}">Kamar {{ $kamar->nomor_kamar }} ({{ $kamar->terisi }}/{{ $kamar->kapasitas }})</option>@endforeach</select></div>
                </div>
                <div class="form-footer"><button class="button light" id="cancel-button" type="button">Batal</button><button class="button" type="submit">Simpan Penghuni</button></div>
            </form>
        </section>
        <section class="panel">
            <div class="panel-head"><div class="tabs"><button class="tab active" type="button" data-tab="active">Penghuni Aktif</button><button class="tab" type="button" data-tab="all">Semua Data</button></div><div class="search"><span>⌕</span><input id="resident-search" placeholder="Cari penghuni, kamar..."></div></div>
            <div class="filters"><select class="filter" id="room-filter"><option value="">Semua Kamar</option>@foreach ($kamars as $kamar)<option value="{{ strtolower($kamar->nomor_kamar) }}">Kamar {{ $kamar->nomor_kamar }}</option>@endforeach</select><select class="filter" id="semester-filter"><option value="">Semua Semester</option>@for ($semester = 1; $semester <= 14; $semester++)<option value="semester {{ $semester }}">Semester {{ $semester }}</option>@endfor</select><select class="filter" id="campus-filter"><option value="">Semua Kampus</option>@foreach ($penghunis->pluck('pendaftaran.institusi_pendidikan')->filter()->unique()->sort() as $kampus)<option value="{{ strtolower($kampus) }}">{{ $kampus }}</option>@endforeach</select></div>
            <div class="table-wrap"><table><thead><tr><th class="number">No</th><th>Penghuni</th><th>Properti / Kamar</th><th>Kampus</th><th>Semester</th><th>ID Asrama</th><th>Masa Tinggal</th><th>Orang Tua / Wali</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="resident-list">
                @forelse ($penghunis as $index => $penghuni)
                    <tr data-search="{{ strtolower($penghuni->name.' '.$penghuni->email.' '.($penghuni->kamar?->nomor_kamar ?? '').' '.($penghuni->pendaftaran?->institusi_pendidikan ?? '').' '.($penghuni->pendaftaran?->nama_orang_tua ?? '')) }}" data-room="{{ strtolower($penghuni->kamar?->nomor_kamar ?? '') }}" data-semester="semester {{ $penghuni->semester }}" data-campus="{{ strtolower($penghuni->pendaftaran?->institusi_pendidikan ?? '') }}">
                        <td class="number">{{ $penghunis->firstItem() + $index }}</td>
<td class="person"><strong>{{ $penghuni->name }}</strong></td>
                         <td class="room"><strong>{{ $penghuni->kamar?->nomor_kamar ? 'Kamar '.$penghuni->kamar->nomor_kamar : 'Belum Dialokasikan' }}</strong></td>
                        <td>{{ $penghuni->pendaftaran?->institusi_pendidikan ?? '-' }}</td>
                         <td><span class="badge blue">SEMESTER {{ $penghuni->semester ?? '-' }}</span></td>
                         <td>{{ $penghuni->id_asrama ?? '-' }}</td><td>{{ $penghuni->tanggal_berakhir?->format('d M Y') ?? '-' }}@if($penghuni->status_penghuni === 'aktif' && $penghuni->tanggal_berakhir && $penghuni->tanggal_berakhir->isBetween(now(), now()->addDays(30)))<small>Segera berakhir</small>@endif</td>
                         <td>{{ $penghuni->pendaftaran?->nama_orang_tua ?? '-' }}<small>{{ $penghuni->pendaftaran?->no_hp_orang_tua ?? '-' }}</small></td>
                         <td><span class="badge {{ $penghuni->status_penghuni === 'aktif' ? 'green' : 'gray' }}">{{ strtoupper($penghuni->status_penghuni ?? 'aktif') }}</span></td>
                        <td class="actions-cell"><form class="room-form" method="POST" action="{{ route('admin.penghuni.update', ['user' => $penghuni]) }}">@csrf @method('PATCH')<input type="hidden" name="name" value="{{ $penghuni->name }}"><input type="hidden" name="nik" value="{{ $penghuni->pendaftaran?->nik }}"><input type="hidden" name="tanggal_lahir" value="{{ $penghuni->pendaftaran?->tanggal_lahir?->format('Y-m-d') }}"><input type="hidden" name="asal_daerah" value="Kutai Kartanegara"><input type="hidden" name="alamat_asal" value="{{ $penghuni->pendaftaran?->alamat_asal }}"><input type="hidden" name="institusi_pendidikan" value="{{ $penghuni->pendaftaran?->institusi_pendidikan }}"><input type="hidden" name="semester" value="{{ $penghuni->semester }}"><input type="hidden" name="no_hp_orang_tua" value="{{ $penghuni->pendaftaran?->no_hp_orang_tua }}"><input type="hidden" name="nama_orang_tua" value="{{ $penghuni->pendaftaran?->nama_orang_tua }}"><select name="kamar_id" aria-label="Pilih kamar"><option value="">Kamar</option>@foreach ($kamars as $kamar)<option value="{{ $kamar->id }}" @selected($penghuni->kamar_id === $kamar->id)>Kamar {{ $kamar->nomor_kamar }} ({{ $kamar->terisi }}/{{ $kamar->kapasitas }})</option>@endforeach</select><button class="save" type="submit">Simpan</button></form><a class="detail" href="{{ route('admin.penghuni.show', ['user' => $penghuni]) }}">Detail</a><form method="POST" action="{{ route('admin.penghuni.status', ['user' => $penghuni]) }}" class="status-form">@csrf @method('PATCH')<select name="status_penghuni" aria-label="Status penghuni"><option value="aktif" @selected($penghuni->status_penghuni === 'aktif')>Aktif</option><option value="alumni" @selected($penghuni->status_penghuni === 'alumni')>Alumni</option><option value="keluar" @selected($penghuni->status_penghuni === 'keluar')>Keluar</option></select><input type="date" name="tanggal_keluar" value="{{ $penghuni->tanggal_keluar?->format('Y-m-d') }}" aria-label="Tanggal keluar"><input name="alasan_keluar" value="{{ $penghuni->alasan_keluar }}" placeholder="Alasan jika keluar"><button class="save" type="submit">Status</button></form></td>
                    </tr>
                @empty
                    <tr><td class="empty" colspan="11">Belum ada data penghuni.</td></tr>
                @endforelse
            </tbody></table></div>
            @if ($penghunis->hasPages()) <div class="pagination">{{ $penghunis->links() }}</div> @endif
        </section>
    </main>
    <script>
        const addPanel = document.querySelector('#add-panel');
document.querySelector('#add-button').addEventListener('click', () => addPanel.classList.toggle('open'));
         document.querySelector('#cancel-button').addEventListener('click', () => addPanel.classList.remove('open'));
         const importPanel = document.querySelector('#import-panel');
         document.querySelector('#import-button').addEventListener('click', () => importPanel.classList.toggle('open'));
         document.querySelector('#cancel-import').addEventListener('click', () => importPanel.classList.remove('open'));
        const search = document.querySelector('#resident-search');
        const globalSearch = document.querySelector('#global-search');
        const room = document.querySelector('#room-filter');
        const semester = document.querySelector('#semester-filter');
        const campus = document.querySelector('#campus-filter');
        const rows = [...document.querySelectorAll('#resident-list tr[data-search]')];
        const applyFilters = () => {
            const query = search.value.toLowerCase().trim();
            rows.forEach((row) => {
                row.hidden = !row.dataset.search.includes(query) || (room.value && row.dataset.room !== room.value) || (semester.value && row.dataset.semester !== semester.value) || (campus.value && row.dataset.campus !== campus.value);
            });
        };
        [search, room, semester, campus].forEach((element) => element.addEventListener('input', applyFilters));
        globalSearch.addEventListener('input', () => { search.value = globalSearch.value; applyFilters(); });
        document.querySelectorAll('[data-tab]').forEach((tab) => tab.addEventListener('click', () => { document.querySelectorAll('.tab').forEach((item) => item.classList.remove('active')); tab.classList.add('active'); }));
        document.addEventListener('keydown', (event) => { if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') { event.preventDefault(); globalSearch.focus(); } });
    </script>
<script>const themeToggle=document.querySelector('#theme-toggle');const setTheme=()=>{const dark=document.documentElement.dataset.theme==='dark';themeToggle.textContent=dark?'☀':'☾';themeToggle.onclick=()=>{const next=document.documentElement.dataset.theme==='dark'?'light':'dark';document.documentElement.dataset.theme=next;localStorage.setItem('admin-theme',next);themeToggle.textContent=next==='dark'?'☀':'☾';};};setTheme();</script></body>
</html>
