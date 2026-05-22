@extends('layouts.app')

@section('title', 'Tambah KK - SiDesa')
@section('eyebrow', 'Administrasi Penduduk')
@section('page-title', 'Tambah Kartu Keluarga')

@section('actions')
    <a href="{{ route('family-cards.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <form class="panel" method="POST" action="{{ route('family-cards.store') }}">
        @include('family-cards._form')
    </form>
@endsection
