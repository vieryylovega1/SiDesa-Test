@extends('layouts.app')

@section('title', 'Detail KK - SiDesa')
@section('eyebrow', 'Administrasi Penduduk')
@section('page-title', 'KK ' . $familyCard->number)

@section('actions')
    <div class="d-flex gap-2">
        @if (auth()->user()?->canAccess('kk.manage'))
            <a href="{{ route('family-cards.edit', $familyCard) }}" class="btn btn-success"><i class="bi bi-pencil me-1"></i> Edit KK</a>
        @endif
        <a href="{{ route('family-cards.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="panel">
                <h5 class="fw-bold mb-3">Informasi KK</h5>
                <div class="d-grid gap-3">
                    <div>
                        <div class="small muted">Kepala Keluarga</div>
                        <div class="fw-semibold">{{ $familyCard->head_name ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="small muted">Alamat</div>
                        <div class="fw-semibold">{{ $familyCard->address ?: '-' }} {{ $familyCard->rt ? 'RT ' . $familyCard->rt . '/RW ' . $familyCard->rw : '' }}</div>
                    </div>
                    <div>
                        <div class="small muted">Dusun</div>
                        <div class="fw-semibold">{{ $familyCard->hamlet ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="small muted">Jumlah Anggota</div>
                        <div class="fs-4 fw-bold">{{ $familyCard->residents->count() }}</div>
                    </div>
                </div>

                @if (auth()->user()?->canAccess('kk.manage') && $familyCard->residents->isEmpty())
                    <form method="POST" action="{{ route('family-cards.destroy', $familyCard) }}" class="mt-4" onsubmit="return confirm('Hapus KK ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger w-100"><i class="bi bi-trash me-1"></i> Hapus KK</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="col-lg-8">
            <div class="panel">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Anggota Keluarga</h5>
                        <div class="small muted">Relasi anggota berdasarkan nomor KK yang sama.</div>
                    </div>
                    @if (auth()->user()?->canAccess('penduduk.manage'))
                        <a href="{{ route('residents.create', ['kk' => $familyCard->number]) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-person-plus me-1"></i> Tambah Anggota</a>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Hubungan</th>
                            <th>TTL</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($familyCard->residents as $resident)
                            <tr>
                                <td class="fw-semibold">{{ $resident->name }}</td>
                                <td>{{ $resident->nik }}</td>
                                <td><span class="badge-soft {{ $resident->family_relationship === 'Kepala Keluarga' ? 'badge-check' : 'badge-process' }}">{{ $resident->family_relationship }}</span></td>
                                <td>{{ $resident->birth_place }}, {{ $resident->birth_date->format('d-m-Y') }}</td>
                                <td class="text-end"><a href="{{ route('residents.show', $resident) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center muted py-5">Belum ada anggota keluarga.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
