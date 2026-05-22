<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Surat - SiDesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: grid; place-items: center; background: #f3f6f5; font-family: "Inter", "Segoe UI", system-ui, sans-serif; }
        .panel { width: min(620px, calc(100vw - 32px)); background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 28px; box-shadow: 0 20px 60px rgba(17,24,39,.08); }
    </style>
</head>
<body>
    <main class="panel">
        <span class="badge text-bg-success mb-3">Surat Valid</span>
        <h1 class="h3 fw-bold">{{ $letter->letter_type }}</h1>
        <p class="text-secondary mb-4">Dokumen ini terdaftar di sistem SiDesa.</p>
        <div class="row g-3">
            <div class="col-md-6"><div class="text-secondary small">Nomor Surat</div><div class="fw-bold">{{ $letter->letter_number }}</div></div>
            <div class="col-md-6"><div class="text-secondary small">Kode Verifikasi</div><div class="fw-bold">{{ $letter->verification_code }}</div></div>
            <div class="col-md-6"><div class="text-secondary small">Nama</div><div class="fw-bold">{{ $letter->applicant_name }}</div></div>
            <div class="col-md-6"><div class="text-secondary small">Tanggal TTD</div><div class="fw-bold">{{ $letter->signed_at?->translatedFormat('d F Y H:i') }}</div></div>
            <div class="col-12"><div class="text-secondary small">Tanda Tangan Digital</div><code style="word-break:break-all;">{{ $letter->digital_signature }}</code></div>
        </div>
    </main>
</body>
</html>
