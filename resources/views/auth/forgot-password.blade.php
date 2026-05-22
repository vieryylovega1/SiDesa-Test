<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - SiDesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: grid; place-items: center; background: #f3f6f5; font-family: "Inter", "Segoe UI", system-ui, sans-serif; }
        .panel { width: min(440px, calc(100vw - 32px)); background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 30px; box-shadow: 0 20px 60px rgba(17,24,39,.08); }
        .btn-success { background: #176b5b; border-color: #176b5b; }
    </style>
</head>
<body>
    <main class="panel">
        <h1 class="h3 fw-bold mb-2">Lupa Password</h1>
        <p class="text-secondary">Masukkan email akun. Link reset akan dikirim melalui sistem mail Laravel.</p>

        @if (session('success'))
            <div class="alert alert-success border-0">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="d-grid gap-3">
            @csrf
            <div>
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" required autofocus>
            </div>
            <button class="btn btn-success btn-lg">Kirim Link Reset</button>
            <a href="{{ route('login') }}" class="text-center text-decoration-none">Kembali ke login</a>
        </form>
    </main>
</body>
</html>
