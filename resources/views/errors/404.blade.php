<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Tidak Ditemukan - SiDesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #f3f6f5;
            color: #1f2937;
            font-family: "Inter", "Segoe UI", system-ui, sans-serif;
        }

        .error-card {
            width: min(620px, calc(100vw - 32px));
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 18px 45px rgba(17, 24, 39, .06);
            padding: 34px;
            text-align: center;
        }

        .error-icon {
            width: 72px;
            height: 72px;
            display: grid;
            place-items: center;
            margin: 0 auto 18px;
            border-radius: 8px;
            background: #e8f4f1;
            color: #176b5b;
            font-size: 2rem;
        }
    </style>
</head>
<body>
<main class="error-card">
    <div class="error-icon"><i class="bi bi-signpost-split"></i></div>
    <div class="text-success fw-bold mb-2">404</div>
    <h1 class="fw-bold mb-2">Halaman tidak ditemukan</h1>
    <p class="text-secondary mb-4">Alamat yang dibuka tidak tersedia, sudah dipindahkan, atau tidak bisa diakses dari akun ini.</p>
    <div class="d-flex flex-wrap justify-content-center gap-2">
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn btn-success">
            <i class="bi bi-house-door me-1"></i> Kembali
        </a>
        @auth
            <a href="{{ route('global-search') }}" class="btn btn-outline-secondary">
                <i class="bi bi-search me-1"></i> Cari Data
            </a>
        @endauth
    </div>
</main>
</body>
</html>
