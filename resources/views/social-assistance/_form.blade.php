@csrf
@isset($recipient)
    @method('PUT')
@endisset

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Penerima</label>
        <select name="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required>
            <option value="">Pilih penduduk</option>
            @foreach ($residents as $resident)
                <option value="{{ $resident->id }}" @selected(old('resident_id', $recipient->resident_id ?? '') == $resident->id)>{{ $resident->name }} - {{ $resident->nik }}</option>
            @endforeach
        </select>
        @error('resident_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Kategori Bantuan</label>
        <select name="social_assistance_category_id" class="form-select @error('social_assistance_category_id') is-invalid @enderror" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('social_assistance_category_id', $recipient->social_assistance_category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('social_assistance_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" @selected(old('status', $recipient->status ?? 'active') === 'active')>Aktif</option>
            <option value="inactive" @selected(old('status', $recipient->status ?? '') === 'inactive')>Nonaktif</option>
            <option value="suspended" @selected(old('status', $recipient->status ?? '') === 'suspended')>Ditangguhkan</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Tanggal Terdaftar</label>
        <input type="date" name="registered_at" value="{{ old('registered_at', isset($recipient) ? $recipient->registered_at->format('Y-m-d') : now()->format('Y-m-d')) }}" class="form-control @error('registered_at') is-invalid @enderror" required>
        @error('registered_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Catatan Kelayakan</label>
        <textarea name="eligibility_note" rows="5" class="form-control @error('eligibility_note') is-invalid @enderror" required>{{ old('eligibility_note', $recipient->eligibility_note ?? '') }}</textarea>
        <div class="small muted mt-1">Wajib diisi sebagai dasar validasi penerima bantuan.</div>
        @error('eligibility_note') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('social-assistance.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Data</button>
</div>
