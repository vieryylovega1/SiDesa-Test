@extends('layouts.app')

@section('title', 'Edit KK - SiDesa')
@section('eyebrow', 'Administrasi Penduduk')
@section('page-title', 'Edit Kartu Keluarga')

@section('actions')
    <a href="{{ route('family-cards.show', $familyCard) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <form class="panel" method="POST" action="{{ route('family-cards.update', $familyCard) }}">
        @include('family-cards._form')
    </form>
@endsection
