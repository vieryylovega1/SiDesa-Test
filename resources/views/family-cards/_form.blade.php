@csrf
@isset($familyCard)
    @method('PUT')
@endisset

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Nomor KK</label>
        <input name="number" value="{{ old('number', $familyCard->number ?? '') }}" class="form-control @error('number') is-invalid @enderror" maxlength="16" required>
        @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Kepala Keluarga</label>
        <input name="head_name" value="{{ old('head_name', $familyCard->head_name ?? '') }}" class="form-control @error('head_name') is-invalid @enderror">
        @error('head_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Alamat</label>
        <input name="address" value="{{ old('address', $familyCard->address ?? '') }}" class="form-control @error('address') is-invalid @enderror">
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">RT</label>
        <input name="rt" value="{{ old('rt', $familyCard->rt ?? '') }}" class="form-control @error('rt') is-invalid @enderror">
        @error('rt') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">RW</label>
        <input name="rw" value="{{ old('rw', $familyCard->rw ?? '') }}" class="form-control @error('rw') is-invalid @enderror">
        @error('rw') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">Dusun</label>
        <input name="hamlet" value="{{ old('hamlet', $familyCard->hamlet ?? '') }}" class="form-control @error('hamlet') is-invalid @enderror">
        @error('hamlet') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('family-cards.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan KK</button>
</div>
