@extends('layouts.app')

@section('title', 'Profil Desa - SiDesa')
@section('eyebrow', 'Pengaturan')
@section('page-title', 'Profil Desa')

@section('content')
    <form class="panel" method="POST" action="{{ route('settings.village-profile.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Desa</label>
                <input name="village_name" value="{{ old('village_name', $profile->village_name) }}" class="form-control @error('village_name') is-invalid @enderror" required>
                @error('village_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Kecamatan</label>
                <input name="district" value="{{ old('district', $profile->district) }}" class="form-control @error('district') is-invalid @enderror" required>
                @error('district') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Kabupaten</label>
                <input name="regency" value="{{ old('regency', $profile->regency) }}" class="form-control @error('regency') is-invalid @enderror" required>
                @error('regency') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Provinsi</label>
                <input name="province" value="{{ old('province', $profile->province) }}" class="form-control @error('province') is-invalid @enderror" required>
                @error('province') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Alamat Kantor Desa</label>
                <input name="address" value="{{ old('address', $profile->address) }}" class="form-control @error('address') is-invalid @enderror">
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Kode Pos</label>
                <input name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}" class="form-control @error('postal_code') is-invalid @enderror">
                @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Telepon</label>
                <input name="phone" value="{{ old('phone', $profile->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email', $profile->email) }}" class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Website</label>
                <input name="website" value="{{ old('website', $profile->website) }}" class="form-control @error('website') is-invalid @enderror" placeholder="https://desa.go.id">
                @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Kepala Desa</label>
                <input name="head_name" value="{{ old('head_name', $profile->head_name) }}" class="form-control @error('head_name') is-invalid @enderror" required>
                @error('head_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">NIP Kepala Desa</label>
                <input name="head_nip" value="{{ old('head_nip', $profile->head_nip) }}" class="form-control @error('head_nip') is-invalid @enderror">
                @error('head_nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Profil</button>
        </div>
    </form>
@endsection
