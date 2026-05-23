@extends('layouts.app')

@section('title', 'Portal Warga - SiDesa')
@section('eyebrow', 'Layanan Warga')
@section('page-title', 'Portal Warga')

@section('content')
    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ route('complaints.create') }}" class="panel d-block text-decoration-none h-100">
                <i class="bi bi-chat-square-text fs-1 text-success"></i>
                <h5 class="fw-bold mt-3 text-body">Kirim Pengaduan</h5>
                <div class="muted">Laporkan masalah lingkungan, pelayanan, atau sosial ke admin desa.</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('letters.index') }}" class="panel d-block text-decoration-none h-100">
                <i class="bi bi-file-earmark-text fs-1 text-success"></i>
                <h5 class="fw-bold mt-3 text-body">Pantau Surat</h5>
                <div class="muted">Lihat status layanan surat dan validasi dokumen yang sudah selesai.</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('news.public.index') }}" class="panel d-block text-decoration-none h-100">
                <i class="bi bi-newspaper fs-1 text-success"></i>
                <h5 class="fw-bold mt-3 text-body">Berita Desa</h5>
                <div class="muted">Ikuti informasi terbaru dari pemerintah desa.</div>
            </a>
        </div>
    </div>

    <div class="panel mt-4">
        <h5 class="fw-bold mb-3">Surat Saya</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Jenis Surat</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Nomor</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($letters as $letter)
                    <tr>
                        <td>{{ $letter->letter_type }}</td>
                        <td>{{ $letter->requested_at?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td><span class="badge-soft badge-process">{{ $letter->status }}</span></td>
                        <td>{{ $letter->letter_number ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <x-empty-state icon="bi-file-earmark-text" title="Belum ada surat" message="Status surat akan tampil di sini jika nama pemohon sesuai akun warga." />
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
