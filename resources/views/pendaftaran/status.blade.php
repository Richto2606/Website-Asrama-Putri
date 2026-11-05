<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Status Pendaftaran</title>
    <style>
        body{font-family:Arial,sans-serif;background:#fdfbf7;color:#171717;margin:0;padding:40px 20px}
        .card{max-width:560px;margin:auto;background:#fff;padding:32px;border:1px solid #ded8cf;border-radius:18px}
        h1{margin-top:0}
        label{display:grid;gap:8px;margin:16px 0;font-weight:700}
        input{padding:12px;border:1px solid #d8d3ce;border-radius:8px;font:inherit}
        .btn{padding:13px 20px;border:0;border-radius:24px;background:#171717;color:#fff;font-weight:700}
        .status{margin-top:24px;padding:18px;background:#f3eee8;border-radius:12px}
        .back{display:inline-block;margin-top:20px}
    </style>
</head>
<body>
    <main class="card">
        <h1>Cek status pendaftaran</h1>
        <p>Masukkan nomor pendaftaran dan email untuk melihat status.</p>
        <form method="POST" action="{{ route('pendaftaran.status.check') }}">
            @csrf
            <label>Nomor pendaftaran
                <input name="nomor_pendaftaran" value="{{ old('nomor_pendaftaran') }}" required>
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <button class="btn" type="submit">Cek status</button>
        </form>
        @if ($errors->any())
            <div class="status">{{ $errors->first() }}</div>
        @endif
        @isset($pendaftaran)
            <div class="status">
                <strong>{{ $pendaftaran->nomor_pendaftaran }}</strong><br>
Status: {{ ucfirst(str_replace('_', ' ', $pendaftaran->status_pendaftaran)) }}
                  @if ($pendaftaran->status_pendaftaran === 'diterima')
                      <p>Kamu akan dihubungi oleh humas asrama. Terima kasih.</p>
                  @elseif ($pendaftaran->status_pendaftaran === 'menunggu')
                      <p>Perbaiki data yang ada dan menunggu kamar tersedia.</p>
                  @elseif ($pendaftaran->status_pendaftaran === 'ditolak')
                      <p>Mohon maaf kamu belum bisa diterima karena kamar masih penuh. Mohon maaf.</p>
                  @elseif ($pendaftaran->catatan_admin)
                      <p><strong>Pesan admin:</strong> {{ $pendaftaran->catatan_admin }}</p>
                  @endif
             </div>
        @endisset
        <a class="back" href="{{ route('pendaftaran.create') }}">Kembali ke pendaftaran</a>
    </main>
</body>
</html>
