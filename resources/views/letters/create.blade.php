@extends('layouts.app')

@section('title', 'Buat Permohonan Surat - SiDesa')
@section('eyebrow', 'Pelayanan Warga')
@section('page-title', 'Buat Permohonan Surat')

@section('actions')
    <a href="{{ route('letters.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
@endsection

@section('content')
    <form class="panel" method="POST" action="{{ route('letters.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Pilih Data Penduduk</label>
                <select name="resident_id" class="form-select @error('resident_id') is-invalid @enderror">
                    <option value="">Pemohon belum terdata</option>
                    @foreach ($residents as $resident)
                        <option value="{{ $resident->id }}" @selected(old('resident_id') == $resident->id)>
                            {{ $resident->name }} - {{ $resident->nik }}
                        </option>
                    @endforeach
                </select>
                @error('resident_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Pemohon</label>
                <input name="applicant_name" value="{{ old('applicant_name') }}" class="form-control @error('applicant_name') is-invalid @enderror" required>
                @error('applicant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Jenis Surat</label>
                <select name="letter_code" class="form-select @error('letter_code') is-invalid @enderror" required>
                    <option value="">Pilih jenis surat</option>
                    @foreach ($letterTypes as $code => $type)
                        <option value="{{ $code }}" @selected(old('letter_code') === $code)>{{ $type['name'] }}</option>
                    @endforeach
                </select>
                @error('letter_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tanggal Pengajuan</label>
                <input type="date" name="requested_at" value="{{ old('requested_at', now()->format('Y-m-d')) }}" class="form-control @error('requested_at') is-invalid @enderror" required>
                @error('requested_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach (['Diajukan', 'Verifikasi', 'Diproses'] as $status)
                        <option @selected(old('status', 'Diajukan') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Nomor HP</label>
                <input name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Keperluan</label>
                <textarea name="purpose" rows="4" class="form-control @error('purpose') is-invalid @enderror" required>{{ old('purpose') }}</textarea>
                @error('purpose') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('letters.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Permohonan</button>
        </div>
    </form>
@endsection
