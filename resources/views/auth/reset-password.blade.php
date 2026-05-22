<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - SiDesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: grid; place-items: center; background: #f3f6f5; font-family: "Inter", "Segoe UI", system-ui, sans-serif; }
        .panel { width: min(460px, calc(100vw - 32px)); background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 30px; box-shadow: 0 20px 60px rgba(17,24,39,.08); }
        .btn-success { background: #176b5b; border-color: #176b5b; }
    </style>
</head>
<body>
    <main class="panel">
        <h1 class="h3 fw-bold mb-2">Reset Password</h1>
        <p class="text-secondary">Buat password baru minimal 8 karakter.</p>

        @if ($errors->any())
            <div class="alert alert-danger border-0">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="d-grid gap-3">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email', request('email')) }}" class="form-control form-control-lg" required autofocus>
            </div>
            <div>
                <label class="form-label fw-semibold">Password Baru</label>
                <input type="password" name="password" class="form-control form-control-lg" required>
            </div>
            <div>
                <label class="form-label fw-semibold">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control form-control-lg" required>
            </div>
            <button class="btn btn-success btn-lg">Simpan Password Baru</button>
        </form>
    </main>
</body>
</html>
