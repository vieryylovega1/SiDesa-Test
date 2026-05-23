<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $report['title'] }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .muted { color: #6b7280; }
        .summary { width: 100%; margin: 14px 0; border-collapse: collapse; }
        .summary td { border: 1px solid #d1d5db; padding: 7px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #d1d5db; padding: 6px; vertical-align: top; }
        table.data th { background: #e8f4f1; text-align: left; }
    </style>
</head>
<body>
    <h1>{{ $report['title'] }}</h1>
    <div class="muted">{{ $report['description'] }}</div>
    <div class="muted">Periode: {{ $filters['start_date'] }} sampai {{ $filters['end_date'] }} | RT: {{ $filters['rt'] ?: 'Semua' }} | RW: {{ $filters['rw'] ?: 'Semua' }}</div>

    <table class="summary">
        <tr>
            @foreach ($report['summary'] as $label => $value)
                <td><strong>{{ $label }}</strong><br>{{ $value }}</td>
            @endforeach
        </tr>
    </table>

    <table class="data">
        <thead>
        <tr>
            @foreach ($report['headers'] as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @forelse ($report['rows'] as $row)
            <tr>
                @foreach ($row as $cell)
                    <td>{{ $cell }}</td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($report['headers']) }}">Tidak ada data.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
