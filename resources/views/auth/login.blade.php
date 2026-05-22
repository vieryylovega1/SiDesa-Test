<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SiDesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background:
                linear-gradient(120deg, rgba(13, 63, 56, .92), rgba(23, 107, 91, .82)),
                url("https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80");
            background-size: cover;
            background-position: center;
            font-family: "Inter", "Segoe UI", system-ui, sans-serif;
        }

        .login-panel {
            width: min(440px, calc(100vw - 32px));
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 28px 80px rgba(0, 0, 0, .22);
        }

        .brand-mark {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #e8f4f1;
            color: #176b5b;
        }

        .btn-success {
            background: #176b5b;
            border-color: #176b5b;
        }
    </style>
</head>
<body>
    <main class="login-panel">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="brand-mark"><i class="bi bi-buildings fs-4"></i></div>
            <div>
                <h1 class="h3 fw-bold mb-0">SiDesa</h1>
                <div class="text-secondary small">Masuk ke sistem informasi desa</div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="d-grid gap-3">
            @csrf
            <div>
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email', 'admin@sidesa.test') }}" class="form-control form-control-lg" required autofocus>
            </div>
            <div>
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control form-control-lg" required>
            </div>
            <label class="form-check">
                <input type="checkbox" name="remember" class="form-check-input">
                <span class="form-check-label">Ingat saya</span>
            </label>
            <button class="btn btn-success btn-lg"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</button>
            <a href="{{ route('password.request') }}" class="text-center text-decoration-none">Lupa password?</a>
        </form>

        <div class="small text-secondary mt-4">
            Akun contoh: superadmin@sidesa.test, admin@sidesa.test, operator@sidesa.test, kepala@sidesa.test, warga@sidesa.test. Password: password
        </div>
    </main>
</body>
</html>
