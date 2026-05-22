@extends('layouts.app')

@section('title', 'Detail Bantuan Sosial - SiDesa')
@section('eyebrow', 'Data Sensitif')
@section('page-title', 'Detail Penerima Bantuan')

@section('actions')
    <div class="d-flex gap-2">
        @if (auth()->user()?->canAccess('bantuan.manage'))
            <a href="{{ route('social-assistance.edit', $recipient) }}" class="btn btn-success"><i class="bi bi-pencil me-1"></i> Edit</a>
        @endif
        <a href="{{ route('social-assistance.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
@endsection

@section('content')
    @php
        $statusBadge = match ($recipient->status) {
            'inactive' => 'badge-danger-soft',
            'suspended' => 'badge-wait',
            default => 'badge-check',
        };
    @endphp

    <div class="alert alert-warning border-0 shadow-sm">
        <strong>Data sensitif.</strong> Gunakan informasi ini hanya untuk verifikasi dan administrasi bantuan sosial desa.
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="panel">
                <h5 class="fw-bold mb-3">Profil Penerima</h5>
                <div class="d-grid gap-3">
                    <div>
                        <div class="small muted">Nama</div>
                        <div class="fw-semibold">{{ $recipient->resident->name }}</div>
                    </div>
                    <div>
                        <div class="small muted">NIK</div>
                        <div class="fw-semibold">{{ $recipient->resident->nik }}</div>
                    </div>
                    <div>
                        <div class="small muted">Nomor KK</div>
                        <div class="fw-semibold">{{ $recipient->resident->kk }}</div>
                    </div>
                    <div>
                        <div class="small muted">Alamat</div>
                        <div class="fw-semibold">{{ $recipient->resident->address }} RT {{ $recipient->resident->rt }}/RW {{ $recipient->resident->rw }}</div>
                    </div>
                    <div>
                        <div class="small muted">Kategori Bantuan</div>
                        <div class="fw-semibold">{{ $recipient->category->name }}</div>
                    </div>
                    <div>
                        <div class="small muted">Status</div>
                        <span class="badge-soft {{ $statusBadge }}">{{ $recipient->status }}</span>
                    </div>
                    <div>
                        <div class="small muted">Tanggal Terdaftar</div>
                        <div class="fw-semibold">{{ $recipient->registered_at->translatedFormat('d M Y') }}</div>
                    </div>
                    <div>
                        <div class="small muted">Dicatat Oleh</div>
                        <div class="fw-semibold">{{ $recipient->creator?->name ?? '-' }}</div>
                    </div>
                </div>

                @if (auth()->user()?->canAccess('bantuan.manage'))
                    <form method="POST" action="{{ route('social-assistance.destroy', $recipient) }}" class="mt-4" onsubmit="return confirm('Hapus data penerima bantuan ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger w-100"><i class="bi bi-trash me-1"></i> Hapus Data</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="col-lg-8">
            <div class="panel mb-4">
                <h5 class="fw-bold mb-2">Catatan Kelayakan</h5>
                <p class="mb-0">{{ $recipient->eligibility_note }}</p>
            </div>

            <div class="panel">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Riwayat Bantuan</h5>
                        <div class="small muted">Catatan penyaluran, penundaan, atau pembatalan bantuan.</div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Petugas</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($recipient->histories->sortByDesc('distributed_at') as $history)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $history->distributed_at->translatedFormat('d M Y') }}</div>
                                    @if ($history->description)
                                        <div class="small muted">{{ $history->description }}</div>
                                    @endif
                                </td>
                                <td>{{ $history->period ?: '-' }}</td>
                                <td>{{ $history->amount ? 'Rp ' . number_format((float) $history->amount, 0, ',', '.') : '-' }}</td>
                                <td>
                                    @php
                                        $historyBadge = match ($history->status) {
                                            'ditunda' => 'badge-wait',
                                            'dibatalkan' => 'badge-danger-soft',
                                            default => 'badge-check',
                                        };
                                    @endphp
                                    <span class="badge-soft {{ $historyBadge }}">{{ $history->status }}</span>
                                </td>
                                <td>{{ $history->recorder?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center muted py-5">Belum ada riwayat bantuan.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if (auth()->user()?->canAccess('bantuan.manage'))
                <form class="panel mt-4" method="POST" action="{{ route('social-assistance.histories.store', $recipient) }}">
                    @csrf
                    <h5 class="fw-bold mb-3">Tambah Riwayat Penyaluran</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal</label>
                            <input type="date" name="distributed_at" value="{{ old('distributed_at', now()->format('Y-m-d')) }}" class="form-control @error('distributed_at') is-invalid @enderror" required>
                            @error('distributed_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Periode</label>
                            <input name="period" value="{{ old('period') }}" class="form-control @error('period') is-invalid @enderror" placeholder="Mei 2026">
                            @error('period') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nominal</label>
                            <input type="number" min="0" step="1000" name="amount" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" placeholder="300000">
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="disalurkan" @selected(old('status', 'disalurkan') === 'disalurkan')>Disalurkan</option>
                                <option value="ditunda" @selected(old('status') === 'ditunda')>Ditunda</option>
                                <option value="dibatalkan" @selected(old('status') === 'dibatalkan')>Dibatalkan</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <input name="description" value="{{ old('description') }}" class="form-control @error('description') is-invalid @enderror" placeholder="Keterangan penyaluran">
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Simpan Riwayat</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
