<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $letter->letter_type }}</title>
    <style>
        body { font-family: "Times New Roman", serif; color: #111; margin: 34px 42px; line-height: 1.5; }
        .kop { text-align: center; border-bottom: 3px double #111; padding-bottom: 12px; margin-bottom: 24px; }
        .kop h2, .kop h3 { margin: 0; }
        .title { text-align: center; text-decoration: underline; font-weight: bold; margin-top: 20px; font-size: 16px; }
        .number { text-align: center; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin: 14px 0; }
        td { padding: 3px 0; vertical-align: top; }
        .label { width: 175px; }
        .sign-wrap { display: table; width: 100%; margin-top: 36px; }
        .qr { display: table-cell; width: 180px; vertical-align: bottom; font-size: 10px; }
        .qr img { width: 96px; height: 96px; }
        .sign { display: table-cell; width: 260px; text-align: center; vertical-align: bottom; }
        .hash { font-size: 9px; word-break: break-all; color: #444; }
        .print-button { float: right; padding: 10px 14px; }
        @media print { .print-button { display: none; } body { margin: 24px; } }
    </style>
</head>
<body>
    @if ($printButton ?? false)
        <button class="print-button" onclick="window.print()">Cetak</button>
    @endif

    <div class="kop">
        <h3>PEMERINTAH KABUPATEN SENTOSA</h3>
        <h3>KECAMATAN HARMONI</h3>
        <h2>DESA SUKAMAJU</h2>
        <div>Jl. Raya Desa Sukamaju No. 01, Kode Pos 55555</div>
    </div>

    <div class="title">{{ strtoupper($letter->letter_type) }}</div>
    <div class="number">Nomor: {{ $letter->letter_number }}</div>

    <p>Yang bertanda tangan di bawah ini, Kepala Desa Sukamaju, Kecamatan Harmoni, Kabupaten Sentosa, menerangkan bahwa:</p>

    <table>
        <tr><td class="label">Nama</td><td>: {{ $letter->applicant_name }}</td></tr>
        <tr><td class="label">NIK</td><td>: {{ $resident?->nik ?? '-' }}</td></tr>
        <tr><td class="label">Tempat/Tanggal Lahir</td><td>: {{ $resident ? $resident->birth_place . ', ' . $resident->birth_date->translatedFormat('d F Y') : '-' }}</td></tr>
        <tr><td class="label">Jenis Kelamin</td><td>: {{ $resident?->gender ?? '-' }}</td></tr>
        <tr><td class="label">Agama</td><td>: {{ $resident?->religion ?? '-' }}</td></tr>
        <tr><td class="label">Pekerjaan</td><td>: {{ $resident?->occupation ?? '-' }}</td></tr>
        <tr><td class="label">Alamat</td><td>: {{ $resident ? $resident->address . ' RT ' . $resident->rt . '/RW ' . $resident->rw : '-' }}</td></tr>
    </table>

    <p>{{ $body }}</p>
    <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>

    <div class="sign-wrap">
        <div class="qr">
            <img src="{{ $qrCode }}" alt="QR Verifikasi">
            <div>Kode: {{ $letter->verification_code }}</div>
            <div class="hash">Hash: {{ $letter->digital_signature }}</div>
        </div>
        <div class="sign">
            <div>Sukamaju, {{ $letter->signed_at?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}</div>
            <div>Kepala Desa Sukamaju</div>
            <br><br><br>
            <strong>H. Suryanto</strong>
            <div style="font-size:11px;">Ditandatangani digital oleh: {{ $letter->signer?->name ?? 'SiDesa' }}</div>
        </div>
    </div>
</body>
</html>
