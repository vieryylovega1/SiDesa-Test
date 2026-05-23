@extends('layouts.app')

@section('title', 'Detail Surat - SiDesa')
@section('eyebrow', 'Detail Layanan')
@section('page-title', $letter->letter_type)

@section('actions')
    <div class="d-flex gap-2">
        @if ($letter->status === 'Selesai')
            <a href="{{ route('letters.pdf', $letter) }}" target="_blank" class="btn btn-success"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
            <a href="{{ route('letters.print', $letter) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer me-1"></i> Cetak</a>
        @else
            <button class="btn btn-outline-secondary" disabled><i class="bi bi-lock me-1"></i> Menunggu Approval</button>
        @endif
        <a href="{{ route('letters.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="panel">
                <h5 class="fw-bold mb-3">Informasi Permohonan</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="small muted">Nama Pemohon</div>
                        <div class="fw-semibold">{{ $letter->applicant_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small muted">Status</div>
                        @php
                            $statusBadge = match ($letter->status) {
                                'Selesai' => 'badge-check',
                                'Ditolak' => 'badge-danger-soft',
                                'Diajukan', 'Verifikasi' => 'badge-wait',
                                default => 'badge-process',
                            };
                        @endphp
                        <span class="badge-soft {{ $statusBadge }}">{{ $letter->status }}</span>
                    </div>
                    <div class="col-md-6">
                        <div class="small muted">Tanggal Pengajuan</div>
                        <div class="fw-semibold">{{ $letter->requested_at->translatedFormat('d M Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small muted">Nomor Surat</div>
                        <div class="fw-semibold">{{ $letter->letter_number ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small muted">Kode Verifikasi</div>
                        <div class="fw-semibold">{{ $letter->verification_code ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small muted">Ditandatangani Oleh</div>
                        <div class="fw-semibold">{{ $letter->signer?->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small muted">Disetujui Oleh</div>
                        <div class="fw-semibold">{{ $letter->approver?->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small muted">Tanggal Approval</div>
                        <div class="fw-semibold">{{ $letter->approved_at?->translatedFormat('d M Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="small muted">Keperluan</div>
                        <div class="fw-semibold">{{ $letter->purpose }}</div>
                    </div>
                    @if ($letter->rejection_reason)
                        <div class="col-12">
                            <div class="small muted">Alasan Penolakan</div>
                            <div class="fw-semibold text-danger">{{ $letter->rejection_reason }}</div>
                        </div>
                    @endif
                    <div class="col-12">
                        <div class="small muted">Tanda Tangan Digital</div>
                        <code style="word-break:break-all;">{{ $letter->digital_signature ?? '-' }}</code>
                    </div>
                </div>
            </div>

            @if (auth()->user()?->canAccess('surat.manage') && $letter->status !== 'Selesai')
                <div class="panel mt-4">
                    <h5 class="fw-bold mb-3">Approval Surat</h5>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <form method="POST" action="{{ route('letters.approve', $letter) }}">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i> Setujui Surat</button>
                        </form>
                    </div>
                    <form method="POST" action="{{ route('letters.reject', $letter) }}">
                        @csrf
                        @method('PATCH')
                        <label class="form-label fw-semibold">Alasan Penolakan</label>
                        <textarea name="rejection_reason" rows="3" class="form-control @error('rejection_reason') is-invalid @enderror" placeholder="Tuliskan alasan jika surat ditolak">{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-outline-danger"><i class="bi bi-x-circle me-1"></i> Tolak Surat</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <div class="col-lg-5">
            <div class="panel">
                <h5 class="fw-bold mb-3">Data Penduduk</h5>
                @if ($letter->resident)
                    <div class="small muted">NIK</div>
                    <div class="fw-semibold mb-3">{{ $letter->resident->nik }}</div>
                    <div class="small muted">Alamat</div>
                    <div class="fw-semibold">{{ $letter->resident->address }} RT {{ $letter->resident->rt }}/RW {{ $letter->resident->rw }}</div>
                @else
                    <div class="muted">Pemohon belum terhubung dengan data penduduk.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
