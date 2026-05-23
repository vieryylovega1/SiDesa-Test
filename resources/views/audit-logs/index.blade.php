@extends('layouts.app')

@section('title', 'Audit Log - SiDesa')
@section('eyebrow', 'Keamanan')
@section('page-title', 'Audit Log')

@section('content')
    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Aktivitas Penting User</h5>
                <div class="small muted">Catatan perubahan data untuk kebutuhan kontrol dan penelusuran.</div>
            </div>
        </div>

        <form method="GET" action="{{ route('audit-logs.index') }}" class="row g-2 mb-4">
            <div class="col-md-10">
                <input type="search" name="cari" value="{{ $filters['cari'] ?? '' }}" class="form-control" placeholder="Cari event atau deskripsi aktivitas">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-success"><i class="bi bi-search me-1"></i> Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Deskripsi</th>
                    <th>IP</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td class="muted">{{ $log->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td>{{ $log->user?->name ?? 'Sistem' }}</td>
                        <td><span class="badge-soft badge-process">{{ $log->event }}</span></td>
                        <td>
                            <div class="fw-semibold">{{ $log->description }}</div>
                            <div class="small muted">{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</div>
                        </td>
                        <td class="muted">{{ $log->ip_address ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-empty-state icon="bi-activity" title="Belum ada audit log" message="Aktivitas penting akan tercatat otomatis setelah data berubah." />
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $logs->links() }}
    </div>
@endsection
