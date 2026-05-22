@extends('layouts.app')

@section('title', 'Detail Pengaduan - SiDesa')
@section('eyebrow', 'Laporan Warga')
@section('page-title', $complaint->ticket_number)

@section('actions')
    <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    @php
        $badge = match ($complaint->status) {
            'selesai' => 'badge-check',
            'ditolak' => 'badge-danger-soft',
            'diproses' => 'badge-wait',
            default => 'badge-process',
        };
    @endphp

    <div class="row g-4">
        <div class="col-xl-8">
            <article class="panel">
                @if ($complaint->photo_path)
                    <img src="{{ asset('storage/' . $complaint->photo_path) }}" alt="{{ $complaint->title }}" class="w-100 mb-3" style="max-height:380px;object-fit:cover;border-radius:8px;">
                @endif
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge-soft badge-process">{{ $complaint->category }}</span>
                    <span class="badge-soft {{ $badge }}">{{ $statuses[$complaint->status] ?? $complaint->status }}</span>
                </div>
                <h4 class="fw-bold">{{ $complaint->title }}</h4>
                <div class="small muted mb-3">Dikirim {{ $complaint->created_at->translatedFormat('d M Y H:i') }}</div>
                <div style="white-space: pre-line;">{{ $complaint->description }}</div>
            </article>

            <div class="panel mt-4">
                <h5 class="fw-bold mb-2">Balasan Admin</h5>
                @if ($complaint->admin_reply)
                    <div style="white-space: pre-line;">{{ $complaint->admin_reply }}</div>
                    <div class="small muted mt-3">Dibalas oleh {{ $complaint->replier?->name ?? '-' }} pada {{ $complaint->replied_at?->translatedFormat('d M Y H:i') }}</div>
                @else
                    <div class="muted">Belum ada balasan admin.</div>
                @endif
            </div>
        </div>

        <div class="col-xl-4">
            <div class="panel mb-4">
                <h5 class="fw-bold mb-3">Informasi Pelapor</h5>
                <div class="d-grid gap-3">
                    <div>
                        <div class="small muted">Nama</div>
                        <div class="fw-semibold">{{ $complaint->reporter_name }}</div>
                    </div>
                    <div>
                        <div class="small muted">Nomor HP</div>
                        <div class="fw-semibold">{{ $complaint->phone ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="small muted">Lokasi</div>
                        <div class="fw-semibold">{{ $complaint->address ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="small muted">Akun Pengirim</div>
                        <div class="fw-semibold">{{ $complaint->reporter?->name ?? '-' }}</div>
                    </div>
                </div>
            </div>

            @if (auth()->user()?->canAccess('complaints.manage'))
                <form class="panel" method="POST" action="{{ route('complaints.response.update', $complaint) }}">
                    @csrf
                    @method('PATCH')
                    <h5 class="fw-bold mb-3">Tindak Lanjut</h5>
                    <div class="d-grid gap-3">
                        <div>
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $complaint->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Balasan Admin</label>
                            <textarea name="admin_reply" rows="6" class="form-control @error('admin_reply') is-invalid @enderror">{{ old('admin_reply', $complaint->admin_reply) }}</textarea>
                            @error('admin_reply') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <button class="btn btn-success w-100 mt-4"><i class="bi bi-check2-circle me-1"></i> Simpan Tindak Lanjut</button>
                </form>
            @endif
        </div>
    </div>
@endsection
