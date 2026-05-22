@extends('layouts.app')

@section('title', 'Pengaduan Warga - SiDesa')
@section('eyebrow', 'Laporan Warga')
@section('page-title', 'Pengaduan Warga')

@section('actions')
    @if (auth()->user()?->canAccess('complaints.create'))
        <a href="{{ route('complaints.create') }}" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Kirim Laporan</a>
    @endif
@endsection

@section('content')
    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Laporan</h5>
                <div class="small muted">Pantau laporan warga, status tindak lanjut, dan balasan admin.</div>
            </div>
        </div>

        <form method="GET" action="{{ route('complaints.index') }}" class="row g-2 mb-4">
            <div class="col-md-5">
                <input type="search" name="cari" value="{{ $filters['cari'] ?? '' }}" class="form-control" placeholder="Cari nomor tiket, pelapor, atau judul">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-success"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Laporan</th>
                    <th>Pelapor</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($complaints as $complaint)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $complaint->title }}</div>
                            <div class="small muted">{{ $complaint->ticket_number }}</div>
                        </td>
                        <td>
                            <div>{{ $complaint->reporter_name }}</div>
                            <div class="small muted">{{ $complaint->phone ?: '-' }}</div>
                        </td>
                        <td>{{ $complaint->category }}</td>
                        <td>
                            @php
                                $badge = match ($complaint->status) {
                                    'selesai' => 'badge-check',
                                    'ditolak' => 'badge-danger-soft',
                                    'diproses' => 'badge-wait',
                                    default => 'badge-process',
                                };
                            @endphp
                            <span class="badge-soft {{ $badge }}">{{ $statuses[$complaint->status] ?? $complaint->status }}</span>
                        </td>
                        <td class="muted">{{ $complaint->created_at->translatedFormat('d M Y') }}</td>
                        <td class="text-end"><a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center muted py-5">Belum ada laporan warga.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $complaints->links() }}
    </div>
@endsection
