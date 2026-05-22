@extends('layouts.app')

@section('title', 'Tambah Bantuan Sosial - SiDesa')
@section('eyebrow', 'Data Sensitif')
@section('page-title', 'Tambah Penerima Bantuan')

@section('actions')
    <a href="{{ route('social-assistance.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-8">
            <form class="panel" method="POST" action="{{ route('social-assistance.store') }}">
                @include('social-assistance._form')
            </form>
        </div>
        <div class="col-xl-4">
            <div class="panel">
                <h5 class="fw-bold mb-2">Kategori Bantuan</h5>
                <div class="small muted mb-3">Tambahkan kategori program sebelum mendaftarkan penerima.</div>

                <form method="POST" action="{{ route('social-assistance-categories.store') }}" class="d-grid gap-3">
                    @csrf
                    <div>
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: PKH" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Keterangan singkat program">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <input type="hidden" name="is_active" value="1">
                    <button class="btn btn-outline-success"><i class="bi bi-tags me-1"></i> Simpan Kategori</button>
                </form>
            </div>

            <div class="panel mt-4">
                <h5 class="fw-bold mb-2">Validasi Penerima</h5>
                <div class="small muted">Catatan kelayakan wajib diisi agar keputusan penerima memiliki dasar pemeriksaan yang jelas.</div>
            </div>
        </div>
    </div>
@endsection
