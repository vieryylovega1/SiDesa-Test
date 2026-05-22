@extends('layouts.app')

@section('title', 'Detail Surat - SiDesa')
@section('eyebrow', 'Detail Layanan')
@section('page-title', $letter->letter_type)

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('letters.pdf', $letter) }}" target="_blank" class="btn btn-success"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
        <a href="{{ route('letters.print', $letter) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer me-1"></i> Cetak</a>
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
                        <div class="fw-semibold">{{ $letter->status }}</div>
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
                    <div class="col-12">
                        <div class="small muted">Keperluan</div>
                        <div class="fw-semibold">{{ $letter->purpose }}</div>
                    </div>
                    <div class="col-12">
                        <div class="small muted">Tanda Tangan Digital</div>
                        <code style="word-break:break-all;">{{ $letter->digital_signature ?? '-' }}</code>
                    </div>
                </div>
            </div>
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
