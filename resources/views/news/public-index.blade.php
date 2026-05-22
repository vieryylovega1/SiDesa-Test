<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Berita Desa - SiDesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f3f6f5; color: #1f2937; font-family: "Inter", "Segoe UI", system-ui, sans-serif; }
        .hero { background: linear-gradient(120deg, rgba(13,63,56,.95), rgba(23,107,91,.86)); color: #fff; padding: 54px 0 42px; }
        .card { border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 18px 45px rgba(17,24,39,.05); }
        .post-img { height: 190px; object-fit: cover; border-radius: 8px 8px 0 0; }
        .category-link { text-decoration: none; border: 1px solid #d1d5db; border-radius: 999px; padding: 8px 12px; color: #1f2937; background: #fff; }
        .category-link.active, .category-link:hover { background: #176b5b; color: #fff; border-color: #176b5b; }
    </style>
</head>
<body>
    <header class="hero">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">Berita Desa Sukamaju</h1>
            <p class="lead mb-0">Informasi kegiatan, pembangunan, layanan, dan kabar terbaru dari desa.</p>
        </div>
    </header>

    <main class="container py-4">
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a class="category-link {{ $activeCategory ? '' : 'active' }}" href="{{ route('news.public.index') }}">Semua</a>
            @foreach ($categories as $category)
                <a class="category-link {{ $activeCategory === $category->slug ? 'active' : '' }}" href="{{ route('news.public.index', ['kategori' => $category->slug]) }}">{{ $category->name }}</a>
            @endforeach
        </div>

        <div class="row g-4">
            @forelse ($posts as $post)
                <div class="col-md-6 col-xl-4">
                    <article class="card h-100">
                        @if ($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" class="post-img w-100" alt="{{ $post->title }}">
                        @endif
                        <div class="card-body">
                            <div class="small text-success fw-semibold mb-2">{{ $post->category->name }} | {{ $post->published_at->translatedFormat('d M Y') }}</div>
                            <h2 class="h5 fw-bold">{{ $post->title }}</h2>
                            <p class="text-secondary">{{ $post->excerpt ?: str($post->content)->limit(130) }}</p>
                            <a href="{{ route('news.public.show', $post) }}" class="btn btn-outline-success">Baca Berita</a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="card p-5 text-center text-secondary">Belum ada berita dipublikasikan.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $posts->links() }}</div>
    </main>
</body>
</html>
