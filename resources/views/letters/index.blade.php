@extends('layouts.app')

@section('title', 'Layanan Surat - SiDesa')
@section('eyebrow', 'Pelayanan Warga')
@section('page-title', 'Layanan Surat')

@section('actions')
    @if (auth()->user()?->canAccess('surat.manage'))
        <a href="{{ route('letters.create') }}" class="btn btn-success"><i class="bi bi-file-earmark-plus me-1"></i> Buat Permohonan</a>
    @endif
@endsection

@section('content')
    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Permohonan Surat</h5>
                <div class="small muted">Kelola proses pengajuan surat warga.</div>
            </div>
        </div>

        <form method="GET" action="{{ route('letters.index') }}" class="row g-2 mb-4">
            <div class="col-md-6">
                <input type="search" name="cari" value="{{ $filters['cari'] ?? '' }}" class="form-control" placeholder="Cari pemohon, NIK, nomor, atau jenis surat">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button class="btn btn-outline-success"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Pemohon</th>
                    <th>Jenis Surat</th>
                    <th>Tanggal</th>
                    <th>Nomor Surat</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($letters as $letter)
                    @php
                        $badge = match ($letter->status) {
                            'Selesai' => 'badge-check',
                            'Verifikasi', 'Diajukan' => 'badge-wait',
                            'Ditolak' => 'badge-danger-soft',
                            default => 'badge-process',
                        };
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $letter->applicant_name }}</div>
                            <div class="small muted">{{ $letter->resident?->nik ?? 'Pemohon umum' }}</div>
                        </td>
                        <td>{{ $letter->letter_type }}</td>
                        <td class="muted">{{ $letter->requested_at?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td>{{ $letter->letter_number ?? '-' }}</td>
                        <td><span class="badge-soft {{ $badge }}">{{ $letter->status }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('letters.show', $letter) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center muted py-5">Belum ada permohonan surat.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $letters->links() }}
    </div>
@endsection
