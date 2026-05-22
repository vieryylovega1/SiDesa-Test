@extends('layouts.app')

@section('title', 'Kartu Keluarga - SiDesa')
@section('eyebrow', 'Administrasi Penduduk')
@section('page-title', 'Kartu Keluarga')

@section('actions')
    @if (auth()->user()?->canAccess('kk.manage'))
        <div class="d-flex flex-wrap gap-2">
            <form method="POST" action="{{ route('family-cards.sync') }}">
                @csrf
                <button class="btn btn-outline-success"><i class="bi bi-arrow-repeat me-1"></i> Sinkron Dari Penduduk</button>
            </form>
            <a href="{{ route('family-cards.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i> Tambah KK</a>
        </div>
    @endif
@endsection

@section('content')
    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Kartu Keluarga</h5>
                <div class="small muted">Pantau kepala keluarga dan jumlah anggota setiap KK.</div>
            </div>
            <form class="d-flex gap-2" method="GET" action="{{ route('family-cards.index') }}">
                <input type="search" name="cari" value="{{ $search }}" class="form-control" placeholder="Cari No KK, kepala, alamat">
                <button class="btn btn-outline-success"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>No KK</th>
                    <th>Kepala Keluarga</th>
                    <th>Alamat</th>
                    <th>Anggota</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($familyCards as $familyCard)
                    <tr>
                        <td class="fw-semibold">{{ $familyCard->number }}</td>
                        <td>{{ $familyCard->head_name ?: '-' }}</td>
                        <td>{{ $familyCard->address ?: '-' }} {{ $familyCard->rt ? 'RT ' . $familyCard->rt . '/RW ' . $familyCard->rw : '' }}</td>
                        <td><span class="badge-soft badge-process">{{ $familyCard->residents_count }} orang</span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('family-cards.show', $familyCard) }}" class="btn btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                @if (auth()->user()?->canAccess('kk.manage'))
                                    <a href="{{ route('family-cards.edit', $familyCard) }}" class="btn btn-outline-success"><i class="bi bi-pencil"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center muted py-5">Belum ada data kartu keluarga.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $familyCards->links() }}
    </div>
@endsection
