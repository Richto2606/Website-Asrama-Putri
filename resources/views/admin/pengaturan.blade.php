<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Admin</title>
    <style>
        body{margin:0;background:#f4f7f3;color:#17201d;font-family:Inter,system-ui,sans-serif}.wrap{max-width:1200px;margin:auto;padding:40px 30px}.head{display:flex;justify-content:space-between;align-items:center}.card{margin-top:20px;padding:22px;border:1px solid #e5e9e6;border-radius:16px;background:#fff;box-shadow:0 14px 40px #17201d12}h1{letter-spacing:-.05em}.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}.field{display:grid;gap:6px}.field label{font-size:11px;font-weight:800}.field input,.field select,.field textarea{padding:11px;border:1px solid #e5e9e6;border-radius:9px;font:inherit}.field textarea{min-height:70px}.button{padding:11px 15px;border:0;border-radius:10px;color:#fff;background:#17201d;font-weight:800;cursor:pointer}.full{grid-column:1/-1}.notice{padding:12px;background:#ecfdf5;color:#166534;border-radius:10px}@media(max-width:700px){.grid{grid-template-columns:1fr}.full{grid-column:auto}}
    </style>
</head>
<body>
<main class="wrap">
    <div class="head"><div><a href="{{ route('admin.dashboard') }}">← Dashboard</a><h1>Pengaturan Website</h1></div></div>
    @if(session('success'))
        <div class="notice">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="notice">{{ $errors->first() }}</div>
    @endif
    <section class="card">
        <h2>Pengaturan operasional</h2>
        <form method="POST" action="{{ route('admin.pengaturan.settings') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid">
                <div class="field"><label>Status pendaftaran</label><select name="status_pendaftaran"><option value="buka" @selected(($settings['status_pendaftaran'] ?? 'buka') === 'buka')>Buka</option><option value="tutup" @selected(($settings['status_pendaftaran'] ?? '') === 'tutup')>Tutup</option></select></div>
                <div class="field"><label>Nominal iuran bulanan</label><input type="number" name="nominal_iuran_bulanan" value="{{ $settings['nominal_iuran_bulanan'] ?? 150000 }}" required></div>
                <div class="field"><label>Bank</label><input name="bank" value="{{ $settings['bank'] ?? '' }}" required></div>
                <div class="field"><label>Nomor rekening</label><input name="nomor_rekening" value="{{ $settings['nomor_rekening'] ?? '' }}" required></div>
                <div class="field"><label>Atas nama</label><input name="atas_nama" value="{{ $settings['atas_nama'] ?? '' }}" required></div>
                <div class="field"><label>Kontak humas</label><input name="kontak_humas" value="{{ $settings['kontak_humas'] ?? '' }}" required></div>
                <div class="field full"><label>Alamat asrama</label><textarea name="alamat_asrama" required>{{ $settings['alamat_asrama'] ?? '' }}</textarea></div><div class="field full"><label>Formulir pendaftaran (PDF/DOC/DOCX, maksimal 5 MB)</label><input type="file" name="formulir_pendaftaran" accept=".pdf,.doc,.docx">@if(!empty($settings['formulir_pendaftaran']))<small>File aktif: <a href="{{ asset('storage/'.$settings['formulir_pendaftaran']) }}" target="_blank" rel="noopener">Lihat formulir</a></small>@endif</div>
            </div>
            <p><button class="button" type="submit">Simpan Pengaturan</button></p>
        </form>
    </section>
    <section class="card">
        <h2>Pengumuman Landing Page</h2>
        <form method="POST" action="{{ route('admin.pengumuman.store') }}">
            @csrf
            <div class="grid"><div class="field"><label>Judul</label><input name="judul" required></div><div class="field full"><label>Isi pengumuman</label><textarea name="isi" required></textarea></div></div>
            <p><button class="button" type="submit">Tambah Pengumuman</button></p>
        </form>
        @foreach($pengumumans as $item)
            <div style="padding:14px 0;border-bottom:1px solid #e5e9e6"><strong>{{ $item->judul }}</strong><p>{{ $item->isi }}</p><form method="POST" action="{{ route('admin.pengumuman.destroy', $item) }}">@csrf @method('DELETE')<button class="button" type="submit">Hapus</button></form></div>
        @endforeach
    </section>
</main>
</body>
</html>
