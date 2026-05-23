@extends('layouts.app')

@section('title', 'Laporan - SiDesa')
@section('eyebrow', 'Laporan Desa')
@section('page-title', 'Laporan')

@section('actions')
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('reports.export.pdf', request()->query()) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
        <a href="{{ route('reports.export.excel', request()->query()) }}" class="btn btn-outline-success"><i class="bi bi-file-earmark-spreadsheet me-1"></i> Excel</a>
    </div>
@endsection

@section('content')
    <div class="panel mb-4">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
            <div class="col-lg-3">
                <label class="form-label fw-semibold">Jenis Laporan</label>
                <select name="type" class="form-select">
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label fw-semibold">Tanggal Awal</label>
                <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="form-control">
            </div>
            <div class="col-lg-2">
                <label class="form-label fw-semibold">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="form-control">
            </div>
            <div class="col-lg-2">
                <label class="form-label fw-semibold">RT</label>
                <input name="rt" value="{{ $filters['rt'] }}" class="form-control" placeholder="001">
            </div>
            <div class="col-lg-2">
                <label class="form-label fw-semibold">RW</label>
                <input name="rw" value="{{ $filters['rw'] }}" class="form-control" placeholder="002">
            </div>
            <div class="col-lg-1 d-grid">
                <button class="btn btn-success"><i class="bi bi-funnel"></i></button>
            </div>
        </form>
    </div>

    <div class="row g-3 mb-4">
        @foreach ($report['summary'] as $label => $value)
            <div class="col-md-4">
                <div class="panel h-100">
                    <div class="small muted">{{ $label }}</div>
                    <div class="fs-4 fw-bold">{{ $value }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">{{ $report['title'] }}</h5>
                <div class="small muted">{{ $report['description'] }}</div>
            </div>
            <span class="badge-soft badge-process">{{ count($report['rows']) }} baris</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
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
                        <td colspan="{{ count($report['headers']) }}">
                            <x-empty-state icon="bi-clipboard-data" title="Data laporan kosong" message="Ubah filter tanggal, RT, RW, atau jenis laporan untuk melihat data lain." />
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
