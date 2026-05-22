<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Export PDF Data Penduduk</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 24px; }
        h1 { margin: 0; font-size: 22px; }
        .muted { color: #666; margin: 4px 0 18px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f1f5f4; }
        button { float: right; padding: 8px 12px; }
        @media print { button { display: none; } body { margin: 12px; } }
    </style>
</head>
<body>
    <button onclick="window.print()">Cetak / Simpan PDF</button>
    <h1>Data Penduduk Desa Sukamaju</h1>
    <div class="muted">Total data: {{ $residents->count() }} | Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</div>
    <table>
        <thead>
        <tr>
            <th>NIK</th>
            <th>No KK</th>
            <th>Hubungan</th>
            <th>Nama</th>
            <th>TTL</th>
            <th>Alamat</th>
            <th>Agama</th>
            <th>Status Nikah</th>
            <th>Pekerjaan</th>
            <th>Pendidikan</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($residents as $resident)
            <tr>
                <td>{{ $resident->nik }}</td>
                <td>{{ $resident->kk }}</td>
                <td>{{ $resident->family_relationship }}</td>
                <td>{{ $resident->name }}</td>
                <td>{{ $resident->birth_place }}, {{ $resident->birth_date->format('d-m-Y') }}</td>
                <td>{{ $resident->address }} RT {{ $resident->rt }}/RW {{ $resident->rw }}</td>
                <td>{{ $resident->religion }}</td>
                <td>{{ $resident->marital_status }}</td>
                <td>{{ $resident->occupation }}</td>
                <td>{{ $resident->education }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
