@extends('layouts.app')

@section('title', 'Tambah Penduduk - SiDesa')
@section('eyebrow', 'Master Data')
@section('page-title', 'Tambah Penduduk')

@section('actions')
    <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <form class="panel" method="POST" action="{{ route('residents.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">NIK</label>
                <input name="nik" value="{{ old('nik') }}" class="form-control @error('nik') is-invalid @enderror" maxlength="16" required>
                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomor KK</label>
                <input name="kk" value="{{ old('kk', request('kk')) }}" class="form-control @error('kk') is-invalid @enderror" maxlength="16" required>
                @error('kk') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Hubungan Keluarga</label>
                <select name="family_relationship" class="form-select @error('family_relationship') is-invalid @enderror" required>
                    @foreach (['Kepala Keluarga', 'Istri', 'Suami', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Famili Lain', 'Anggota Keluarga'] as $relationship)
                        <option @selected(old('family_relationship') === $relationship)>{{ $relationship }}</option>
                    @endforeach
                </select>
                @error('family_relationship') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Foto</label>
                <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jenis Kelamin</label>
                <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                    <option value="">Pilih</option>
                    <option @selected(old('gender') === 'Laki-laki')>Laki-laki</option>
                    <option @selected(old('gender') === 'Perempuan')>Perempuan</option>
                </select>
                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tempat Lahir</label>
                <input name="birth_place" value="{{ old('birth_place') }}" class="form-control @error('birth_place') is-invalid @enderror" required>
                @error('birth_place') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="form-control @error('birth_date') is-invalid @enderror" required>
                @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Agama</label>
                <input name="religion" value="{{ old('religion', 'Islam') }}" class="form-control @error('religion') is-invalid @enderror" required>
                @error('religion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Pekerjaan</label>
                <input name="occupation" value="{{ old('occupation') }}" class="form-control @error('occupation') is-invalid @enderror">
                @error('occupation') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Pendidikan</label>
                <select name="education" class="form-select @error('education') is-invalid @enderror" required>
                    @foreach (['Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'D1/D2/D3', 'S1', 'S2', 'S3'] as $education)
                        <option @selected(old('education') === $education)>{{ $education }}</option>
                    @endforeach
                </select>
                @error('education') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Status Kawin</label>
                <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror" required>
                    <option @selected(old('marital_status') === 'Belum Kawin')>Belum Kawin</option>
                    <option @selected(old('marital_status') === 'Kawin')>Kawin</option>
                    <option @selected(old('marital_status') === 'Cerai Hidup')>Cerai Hidup</option>
                    <option @selected(old('marital_status') === 'Cerai Mati')>Cerai Mati</option>
                </select>
                @error('marital_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Status Penduduk</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option @selected(old('status') === 'Aktif')>Aktif</option>
                    <option @selected(old('status') === 'Pindah')>Pindah</option>
                    <option @selected(old('status') === 'Meninggal')>Meninggal</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Alamat</label>
                <input name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" required>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">RT</label>
                <input name="rt" value="{{ old('rt') }}" class="form-control @error('rt') is-invalid @enderror" required>
                @error('rt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">RW</label>
                <input name="rw" value="{{ old('rw') }}" class="form-control @error('rw') is-invalid @enderror" required>
                @error('rw') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Dusun</label>
                <input name="hamlet" value="{{ old('hamlet') }}" class="form-control @error('hamlet') is-invalid @enderror">
                @error('hamlet') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Data</button>
        </div>
    </form>
@endsection
