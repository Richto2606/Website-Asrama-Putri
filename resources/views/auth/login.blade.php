<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin | Asrama Putri Junjung Buyah</title>
    <link rel="icon" href="{{ asset('images/logo-siap-kukar.png') }}">
    <style>
        :root{--y:#ffe51f;--b:#171717;--muted:#777}
        *{box-sizing:border-box}
        body{min-height:100vh;margin:0;display:grid;place-items:center;background:linear-gradient(135deg,#171717,#3a3a3a);font-family:Arial,sans-serif;color:var(--b)}
        .box{display:grid;grid-template-columns:1fr 1fr;width:min(900px,92%);overflow:hidden;border-radius:22px;background:#fff;box-shadow:0 25px 80px #0006}
        .intro{display:flex;flex-direction:column;justify-content:center;padding:55px;background:var(--y)}
        .intro img{width:85px;height:85px;margin-bottom:28px;border:5px solid #fff;border-radius:50%;background:#fff}
        .intro h1{margin:0 0 15px;font-size:42px;line-height:1;letter-spacing:-.06em}
        .intro p{max-width:300px;margin:0;color:#514b00;line-height:1.7}
        .form{padding:55px}.form h2{margin:0 0 9px;font-size:28px}.form>p{margin:0 0 30px;color:var(--muted);font-size:14px}
        .error,.success{margin-bottom:18px;padding:12px;border-radius:8px;font-size:13px}.error{color:#8d3232;background:#fff0f0}.success{color:#166534;background:#ecfdf5}
        .field{display:grid;gap:7px;margin-bottom:18px}.field label{font-size:13px;font-weight:800}.field input{width:100%;padding:14px;border:1px solid #ddd;border-radius:9px;outline:0;font:inherit}.password{display:flex;gap:8px}.password input{flex:1}.password button{border:1px solid #ddd;border-radius:9px;background:#fff;cursor:pointer}
        .remember{display:flex;gap:8px;align-items:center;margin:4px 0 14px;color:var(--muted);font-size:13px}.forgot{display:block;margin-bottom:20px;color:#756900;font-size:13px}.button{width:100%;padding:15px;border:0;border-radius:9px;background:var(--b);color:#fff;font:inherit;font-weight:800;cursor:pointer}.back{display:block;margin-top:18px;color:var(--muted);font-size:13px;text-align:center}
        @media(max-width:680px){.box{grid-template-columns:1fr}.intro{padding:32px}.intro h1{font-size:32px}.intro img{width:60px;height:60px;margin-bottom:18px}.form{padding:32px}}
    </style>
</head>
<body>
    <main class="box">
        <section class="intro">
            <img src="{{ asset('images/logo-siap-kukar.png') }}" alt="Logo Asrama">
            <h1>Ruang pengelola.</h1>
            <p>Kelola data penghuni, pendaftaran, kamar, dan operasional asrama dengan tertib.</p>
        </section>
        <section class="form">
            <h2>Login admin</h2>
            <p>Masuk untuk melanjutkan ke dashboard.</p>
            @if (session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Kata sandi</label>
                    <div class="password">
                        <input id="password" type="password" name="password" required>
                        <button type="button" onclick="const p=document.querySelector('#password');p.type=p.type==='password'?'text':'password'" aria-label="Tampilkan kata sandi">◉</button>
                    </div>
                </div>
                <label class="remember"><input type="checkbox" name="remember"> Ingat saya</label>
                <a class="forgot" href="{{ route('password.request') }}">Lupa kata sandi?</a>
                <a class="forgot" href="{{ route('superadmin.login') }}">Login Super Admin</a>
                <button class="button" type="submit">Masuk ke Dashboard</button>
            </form>
            <a class="back" href="{{ route('home') }}">Kembali ke beranda</a>
        </section>
    </main>
</body>
</html>
