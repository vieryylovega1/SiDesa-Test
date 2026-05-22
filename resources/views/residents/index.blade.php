@extends('layouts.app')

@section('title', 'Data Penduduk - SiDesa')
@section('eyebrow', 'Master Data')
@section('page-title', 'Data Penduduk')

@section('actions')
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('residents.export.pdf', request()->query()) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
        <a href="{{ route('residents.export.excel', request()->query()) }}" class="btn btn-outline-success"><i class="bi bi-file-earmark-spreadsheet me-1"></i> Excel</a>
    @if (auth()->user()?->canAccess('penduduk.manage'))
        <a href="{{ route('residents.create') }}" class="btn btn-success"><i class="bi bi-person-plus me-1"></i> Tambah Penduduk</a>
    @endif
    </div>
@endsection

@section('content')
    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Penduduk</h5>
                <div class="small muted">Cari dan kelola data warga desa.</div>
            </div>
        </div>

        <form class="mb-4" method="GET" action="{{ route('residents.index') }}">
            <div class="row g-2">
                <div class="col-md-8">
                    <input type="search" name="cari" value="{{ $filters['cari'] ?? '' }}" class="form-control" placeholder="Cari nama, NIK, atau KK">
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#advancedResidentFilter">
                        <i class="bi bi-sliders me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-outline-success"><i class="bi bi-search me-1"></i> Cari</button>
                </div>
            </div>

            <div class="collapse mt-3 {{ collect($filters)->except('cari')->filter()->isNotEmpty() ? 'show' : '' }}" id="advancedResidentFilter">
                <div class="border rounded-3 p-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Semua gender</option>
                                @foreach ($filterOptions['genders'] as $gender)
                                    <option value="{{ $gender }}" @selected(($filters['gender'] ?? '') === $gender)>{{ $gender }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Pendidikan</label>
                            <select name="education" class="form-select">
                                <option value="">Semua pendidikan</option>
                                @foreach ($filterOptions['educations'] as $education)
                                    <option value="{{ $education }}" @selected(($filters['education'] ?? '') === $education)>{{ $education }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua status</option>
                                @foreach ($filterOptions['statuses'] as $status)
                                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">RT</label>
                            <select name="rt" class="form-select">
                                <option value="">Semua RT</option>
                                @foreach ($filterOptions['rt'] as $rt)
                                    <option value="{{ $rt }}" @selected(($filters['rt'] ?? '') === $rt)>{{ $rt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">RW</label>
                            <select name="rw" class="form-select">
                                <option value="">Semua RW</option>
                                @foreach ($filterOptions['rw'] as $rw)
                                    <option value="{{ $rw }}" @selected(($filters['rw'] ?? '') === $rw)>{{ $rw }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if (auth()->user()?->canAccess('penduduk.manage'))
            <form class="border rounded-3 p-3 mb-4" method="POST" action="{{ route('residents.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Import Excel CSV</label>
                        <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
                        <div class="small muted mt-1">Header: NIK, No KK, Hubungan Keluarga, Nama, Jenis Kelamin, Tempat Lahir, Tanggal Lahir, Alamat, RT, RW, Agama, Status Nikah, Pekerjaan, Pendidikan, Status.</div>
                    </div>
                    <div class="col-md-4 d-grid">
                        <button class="btn btn-success"><i class="bi bi-upload me-1"></i> Import Data</button>
                    </div>
                </div>
            </form>
        @endif

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>KK</th>
                    <th>Alamat</th>
                    <th>Pendidikan</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($residents as $resident)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $resident->name }}</div>
                            <div class="small muted">{{ $resident->gender }}, {{ $resident->birth_date->age }} tahun</div>
                        </td>
                        <td>{{ $resident->nik }}</td>
                        <td>{{ $resident->kk }}</td>
                        <td>{{ $resident->address }} RT {{ $resident->rt }}/RW {{ $resident->rw }}</td>
                        <td>{{ $resident->education }}</td>
                        <td><span class="badge-soft badge-check">{{ $resident->status }}</span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('residents.show', $resident) }}" class="btn btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                @if (auth()->user()?->canAccess('penduduk.manage'))
                                    <a href="{{ route('residents.edit', $resident) }}" class="btn btn-outline-success"><i class="bi bi-pencil"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center muted py-5">Belum ada data penduduk.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $residents->links() }}
    </div>
@endsection
