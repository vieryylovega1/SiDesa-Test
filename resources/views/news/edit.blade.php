@extends('layouts.app')

@section('title', 'Edit Berita - SiDesa')
@section('eyebrow', 'Informasi Publik')
@section('page-title', 'Edit Berita Desa')

@section('actions')
    <a href="{{ route('news.show', $post) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <form class="panel" method="POST" action="{{ route('news.update', $post) }}" enctype="multipart/form-data">
        @include('news._form')
    </form>
@endsection
