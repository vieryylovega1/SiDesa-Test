@extends('layouts.app')

@section('title', 'Tambah User - SiDesa')
@section('eyebrow', 'Keamanan Sistem')
@section('page-title', 'Tambah User')

@section('actions')
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <form class="panel" method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama</label>
                <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Role</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    @foreach (config('sidesa.roles') as $value => $label)
                        <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="is_active" class="form-select @error('is_active') is-invalid @enderror" required>
                    <option value="1" @selected(old('is_active', '1') === '1')>Aktif</option>
                    <option value="0" @selected(old('is_active') === '0')>Nonaktif</option>
                </select>
                @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan User</button>
        </div>
    </form>
@endsection
