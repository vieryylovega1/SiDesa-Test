@extends('layouts.app')

@section('title', 'Tulis Berita - SiDesa')
@section('eyebrow', 'Informasi Publik')
@section('page-title', 'Tulis Berita Desa')

@section('actions')
    <a href="{{ route('news.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-8">
            <form class="panel" method="POST" action="{{ route('news.store') }}" enctype="multipart/form-data">
                @include('news._form')
            </form>
        </div>
        <div class="col-xl-4">
            <div class="panel">
                <h5 class="fw-bold mb-3">Tambah Kategori</h5>
                <form method="POST" action="{{ route('news-categories.store') }}" class="d-grid gap-2">
                    @csrf
                    <input name="name" class="form-control" placeholder="Contoh: Pembangunan" required>
                    <button class="btn btn-outline-success"><i class="bi bi-tags me-1"></i> Simpan Kategori</button>
                </form>
            </div>
        </div>
    </div>
@endsection
