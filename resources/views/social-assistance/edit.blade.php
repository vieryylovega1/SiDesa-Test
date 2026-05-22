@extends('layouts.app')

@section('title', 'Edit Bantuan Sosial - SiDesa')
@section('eyebrow', 'Data Sensitif')
@section('page-title', 'Edit Penerima Bantuan')

@section('actions')
    <a href="{{ route('social-assistance.show', $recipient) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <form class="panel" method="POST" action="{{ route('social-assistance.update', $recipient) }}">
        @include('social-assistance._form')
    </form>
@endsection
