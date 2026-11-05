<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi</title>
    <style>
        body{font-family:Arial,sans-serif;background:#171717;display:grid;place-items:center;min-height:100vh;margin:0}
        .box{background:#fff;padding:32px;border-radius:16px;width:min(440px,90%)}
        h1{margin-top:0}.message{padding:14px;background:#fff8d6;color:#665600;border-radius:8px;line-height:1.6}
        .button{display:block;margin-top:22px;padding:13px 18px;border-radius:8px;background:#171717;color:#fff;font-weight:700;text-align:center;text-decoration:none}
        .back{display:block;margin-top:20px;color:#666}
    </style>
</head>
<body>
    <main class="box">
        <h1>Lupa kata sandi</h1>
        <div class="message">Untuk keamanan akun, reset kata sandi harus melalui super admin. Hubungi super admin untuk mengubah kata sandi melalui menu kelola akun pengurus.</div>
        <a class="button" href="{{ route('login') }}">Kembali ke login</a>
        <a class="back" href="{{ route('home') }}">Kembali ke beranda</a>
    </main>
</body>
</html>
