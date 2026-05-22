@extends('layouts.app')

@section('title', 'Kirim Pengaduan - SiDesa')
@section('eyebrow', 'Laporan Warga')
@section('page-title', 'Kirim Pengaduan')

@section('actions')
    <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <form class="panel" method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Pelapor</label>
                <input name="reporter_name" value="{{ old('reporter_name', $user->name) }}" class="form-control @error('reporter_name') is-invalid @enderror" required>
                @error('reporter_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomor HP</label>
                <input name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Alamat/Keterangan Lokasi</label>
                <input name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" placeholder="Contoh: RT 001/RW 002, dekat balai dusun">
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-7">
                <label class="form-label fw-semibold">Judul Laporan</label>
                <input name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Jalan berlubang di depan pos ronda" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Isi Laporan</label>
                <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Foto Pendukung</label>
                <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                <div class="small muted mt-1">Opsional. Maksimal 3 MB.</div>
                @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-success"><i class="bi bi-send me-1"></i> Kirim Laporan</button>
        </div>
    </form>
@endsection
