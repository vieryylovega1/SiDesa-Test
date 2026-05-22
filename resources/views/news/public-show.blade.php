<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} - Berita Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f3f6f5; color: #1f2937; font-family: "Inter", "Segoe UI", system-ui, sans-serif; }
        .article { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 28px; box-shadow: 0 18px 45px rgba(17,24,39,.05); }
        .cover { max-height: 430px; object-fit: cover; border-radius: 8px; }
        .comment { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 18px; }
    </style>
</head>
<body>
    <main class="container py-4">
        <a href="{{ route('news.public.index') }}" class="btn btn-outline-secondary mb-3">Kembali</a>

        <article class="article mb-4">
            <div class="text-success fw-semibold mb-2">{{ $post->category->name }} | {{ $post->published_at->translatedFormat('d F Y') }}</div>
            <h1 class="display-6 fw-bold">{{ $post->title }}</h1>
            <p class="text-secondary">Ditulis oleh {{ $post->author?->name ?? 'SiDesa' }}</p>
            @if ($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" class="cover w-100 my-3" alt="{{ $post->title }}">
            @endif
            <div style="white-space: pre-line; line-height: 1.75;">{{ $post->content }}</div>
        </article>

        @if (session('success'))
            <div class="alert alert-success border-0">{{ session('success') }}</div>
        @endif

        <section class="row g-4">
            <div class="col-lg-7">
                <h2 class="h5 fw-bold mb-3">Komentar</h2>
                <div class="d-grid gap-3">
                    @forelse ($post->comments as $comment)
                        <div class="comment">
                            <div class="fw-semibold">{{ $comment->name }}</div>
                            <div class="small text-secondary mb-2">{{ $comment->created_at->translatedFormat('d M Y H:i') }}</div>
                            <div>{{ $comment->comment }}</div>
                        </div>
                    @empty
                        <div class="text-secondary">Belum ada komentar yang disetujui.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-5">
                <form class="article" method="POST" action="{{ route('news.public.comment', $post) }}">
                    @csrf
                    <h2 class="h5 fw-bold mb-3">Kirim Komentar</h2>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Komentar</label>
                        <textarea name="comment" rows="4" class="form-control" required></textarea>
                    </div>
                    <button class="btn btn-success">Kirim Komentar</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
