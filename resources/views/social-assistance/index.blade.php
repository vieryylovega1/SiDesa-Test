@extends('layouts.app')

@section('title', 'Bantuan Sosial - SiDesa')
@section('eyebrow', 'Data Sensitif')
@section('page-title', 'Data Bantuan Sosial')

@section('actions')
    @if (auth()->user()?->canAccess('bantuan.manage'))
        <a href="{{ route('social-assistance.create') }}" class="btn btn-success"><i class="bi bi-person-plus me-1"></i> Tambah Penerima</a>
    @endif
@endsection

@section('content')
    <div class="alert alert-warning border-0 shadow-sm">
        <strong>Data sensitif.</strong> Akses dan perubahan data penerima bantuan harus berdasarkan hasil validasi kelayakan.
    </div>

    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Penerima Bantuan</h5>
                <div class="small muted">Kelola penerima, status aktif, kategori, dan riwayat penyaluran.</div>
            </div>
        </div>

        <form method="GET" action="{{ route('social-assistance.index') }}" class="row g-2 mb-4">
            <div class="col-md-5">
                <input type="search" name="cari" value="{{ $filters['cari'] ?? '' }}" class="form-control" placeholder="Cari nama, NIK, atau KK">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option>
                    <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Nonaktif</option>
                    <option value="suspended" @selected(($filters['status'] ?? '') === 'suspended')>Ditangguhkan</option>
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
                    <th>Penerima</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th>Riwayat</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($recipients as $recipient)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $recipient->resident->name }}</div>
                            <div class="small muted">NIK {{ $recipient->resident->nik }} | KK {{ $recipient->resident->kk }}</div>
                        </td>
                        <td>{{ $recipient->category->name }}</td>
                        <td>
                            @php
                                $badge = match ($recipient->status) {
                                    'inactive' => 'badge-danger-soft',
                                    'suspended' => 'badge-wait',
                                    default => 'badge-check',
                                };
                            @endphp
                            <span class="badge-soft {{ $badge }}">{{ $recipient->status }}</span>
                        </td>
                        <td class="muted">{{ $recipient->registered_at->translatedFormat('d M Y') }}</td>
                        <td>{{ $recipient->histories_count }} penyaluran</td>
                        <td class="text-end"><a href="{{ route('social-assistance.show', $recipient) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center muted py-5">Belum ada data penerima bantuan.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $recipients->links() }}
    </div>
@endsection
