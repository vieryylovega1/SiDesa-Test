@extends('layouts.app')

@section('title', $resident->name . ' - SiDesa')
@section('eyebrow', 'Detail Penduduk')
@section('page-title', $resident->name)

@section('actions')
    <div class="d-flex gap-2">
        @if (auth()->user()?->canAccess('penduduk.manage'))
            <a href="{{ route('residents.edit', $resident) }}" class="btn btn-success"><i class="bi bi-pencil me-1"></i> Edit</a>
        @endif
        <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="panel">
                <div class="d-flex gap-3 align-items-center mb-3">
                    @if ($resident->photo_path)
                        <img src="{{ asset('storage/' . $resident->photo_path) }}" alt="{{ $resident->name }}" style="width:72px;height:72px;object-fit:cover;border-radius:8px;">
                    @else
                        <div class="d-grid place-items-center bg-light text-secondary" style="width:72px;height:72px;border-radius:8px;display:grid;place-items:center;">
                            <i class="bi bi-person fs-2"></i>
                        </div>
                    @endif
                    <h5 class="fw-bold mb-0">Biodata Warga</h5>
                </div>
                <div class="row g-3">
                    @foreach ([
                        'NIK' => $resident->nik,
                        'Nomor KK' => $resident->kk,
                        'Hubungan Keluarga' => $resident->family_relationship,
                        'Jenis Kelamin' => $resident->gender,
                        'Tempat/Tanggal Lahir' => $resident->birth_place . ', ' . $resident->birth_date->translatedFormat('d M Y'),
                        'Agama' => $resident->religion,
                        'Pekerjaan' => $resident->occupation ?: '-',
                        'Pendidikan' => $resident->education ?: '-',
                        'Status Kawin' => $resident->marital_status,
                        'Status Penduduk' => $resident->status,
                    ] as $label => $value)
                        <div class="col-md-6">
                            <div class="small muted">{{ $label }}</div>
                            <div class="fw-semibold">{{ $value }}</div>
                        </div>
                    @endforeach
                    <div class="col-12">
                        <div class="small muted">Alamat</div>
                        <div class="fw-semibold">{{ $resident->address }} RT {{ $resident->rt }}/RW {{ $resident->rw }} {{ $resident->hamlet ? '- Dusun ' . $resident->hamlet : '' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="small muted">Relasi Kartu Keluarga</div>
                        <div class="fw-semibold">{{ $resident->familyCard?->number ?? $resident->kk }} - Kepala KK: {{ $resident->familyCard?->head_name ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="panel mb-4">
                <h5 class="fw-bold mb-3">Riwayat Surat</h5>
                <div class="d-grid gap-3">
                    @forelse ($resident->letterRequests as $letter)
                        <a href="{{ route('letters.show', $letter) }}" class="text-decoration-none text-dark border rounded-3 p-3">
                            <div class="fw-bold">{{ $letter->letter_type }}</div>
                            <div class="small muted">{{ $letter->requested_at->translatedFormat('d M Y') }} - {{ $letter->status }}</div>
                        </a>
                    @empty
                        <div class="muted">Belum ada riwayat permohonan surat.</div>
                    @endforelse
                </div>
            </div>
            @if (auth()->user()?->canAccess('penduduk.delete'))
                <form method="POST" action="{{ route('residents.destroy', $resident) }}" class="panel" onsubmit="return confirm('Hapus data penduduk ini?')">
                    @csrf
                    @method('DELETE')
                    <h5 class="fw-bold mb-2">Hapus Data</h5>
                    <p class="muted mb-3">Gunakan hanya jika data benar-benar salah atau tidak dibutuhkan.</p>
                    <button class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i> Hapus Penduduk</button>
                </form>
            @endif
        </div>
    </div>
@endsection
