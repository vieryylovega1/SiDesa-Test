@csrf
@isset($post)
    @method('PUT')
@endisset

<div class="row g-3">
    <div class="col-lg-8">
        <label class="form-label fw-semibold">Judul Berita</label>
        <input name="title" value="{{ old('title', $post->title ?? '') }}" class="form-control @error('title') is-invalid @enderror" required>
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-lg-4">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="draft" @selected(old('status', $post->status ?? 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $post->status ?? '') === 'published')>Published</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-lg-6">
        <label class="form-label fw-semibold">Kategori</label>
        <select name="news_category_id" class="form-select @error('news_category_id') is-invalid @enderror" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('news_category_id', $post->news_category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('news_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-lg-6">
        <label class="form-label fw-semibold">Gambar Utama</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
        @isset($post)
            @if ($post->image_path)
                <div class="small muted mt-1">Gambar saat ini sudah tersimpan.</div>
            @endif
        @endisset
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Ringkasan</label>
        <textarea name="excerpt" rows="3" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
        @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Isi Berita</label>
        <textarea name="content" rows="12" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $post->content ?? '') }}</textarea>
        @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Berita</button>
</div>
