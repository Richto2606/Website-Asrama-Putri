<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Admin | SIAP-Kukar</title>
    <style>
        :root { --yellow: #ffe51f; --black: #171717; --muted: #777; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; display: grid; place-items: center; background: linear-gradient(135deg, #171717, #3a3a3a); color: var(--black); font-family: Inter, system-ui, sans-serif; }
        .box { width: min(520px, 92%); padding: 42px; border-radius: 20px; background: #fff; box-shadow: 0 25px 80px #0006; }
        h1 { margin: 0 0 8px; font-size: 30px; }
        p { margin: 0 0 25px; color: var(--muted); font-size: 14px; }
        .errors { margin-bottom: 18px; padding: 12px 28px; border-radius: 8px; color: #8d3232; background: #fff0f0; font-size: 13px; }
        .field { display: grid; gap: 7px; margin-bottom: 17px; }
        label { font-size: 13px; font-weight: 800; }
        input { width: 100%; padding: 13px; border: 1px solid #ddd; border-radius: 9px; font: inherit; }
        button { width: 100%; padding: 14px; border: 0; border-radius: 9px; color: #fff; background: var(--black); font: inherit; font-weight: 800; cursor: pointer; }
        .back { display: block; margin-top: 18px; color: var(--muted); font-size: 13px; text-align: center; text-decoration: none; }
    </style>
</head>
<body>
    <main class="box">
        <h1>Register Admin</h1>
        <p>Buat akun admin untuk mengelola SIAP-Kukar.</p>
        @if ($errors->any()) <div class="errors"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
        <form method="POST" action="{{ route('admin.register.store') }}">
            @csrf
            <div class="field"><label>Nama admin</label><input name="name" value="{{ old('name') }}" required autofocus></div>
            <div class="field"><label>Email admin</label><input type="email" name="email" value="{{ old('email') }}" required></div>
            <div class="field"><label>Password</label><input type="password" name="password" minlength="8" required></div>
            <div class="field"><label>Konfirmasi password</label><input type="password" name="password_confirmation" minlength="8" required></div>
            <button type="submit">Buat akun admin</button>
        </form>
        <a class="back" href="{{ route('login') }}">Sudah punya akun? Login</a>
    </main>
</body>
</html>
