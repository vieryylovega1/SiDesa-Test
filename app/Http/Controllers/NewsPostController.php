<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use App\Models\NewsComment;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsPostController extends Controller
{
    public function publicIndex(Request $request)
    {
        $category = $request->string('kategori')->toString();

        $posts = NewsPost::with('category')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->when($category, fn ($query) => $query->whereHas('category', fn ($query) => $query->where('slug', $category)))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('news.public-index', [
            'posts' => $posts,
            'categories' => NewsCategory::orderBy('name')->get(),
            'activeCategory' => $category,
        ]);
    }

    public function publicShow(NewsPost $post)
    {
        abort_if($post->status !== 'published', 404);

        return view('news.public-show', [
            'post' => $post->load(['category', 'author', 'comments' => fn ($query) => $query->where('status', 'approved')->latest()]),
        ]);
    }

    public function comment(Request $request, NewsPost $post)
    {
        abort_if($post->status !== 'published', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:120'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $post->comments()->create($data);

        return back()->with('success', 'Komentar dikirim dan menunggu moderasi.');
    }

    public function index(Request $request)
    {
        $search = $request->string('cari')->toString();

        $posts = NewsPost::with(['category', 'author'])
            ->withCount('comments')
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('news.index', compact('posts', 'search'));
    }

    public function create()
    {
        return view('news.create', [
            'categories' => NewsCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['user_id'] = auth()->id();
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('news', 'public');
        }

        NewsPost::create($data);

        return redirect()->route('news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(NewsPost $news)
    {
        return view('news.show', [
            'post' => $news->load(['category', 'author', 'comments' => fn ($query) => $query->latest()]),
        ]);
    }

    public function edit(NewsPost $news)
    {
        return view('news.edit', [
            'post' => $news,
            'categories' => NewsCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, NewsPost $news)
    {
        $data = $this->validatedData($request);
        $data['slug'] = $news->title !== $data['title'] ? $this->uniqueSlug($data['title'], $news->id) : $news->slug;
        $data['published_at'] = $data['status'] === 'published'
            ? ($news->published_at ?: now())
            : null;

        if ($request->hasFile('image')) {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
            $data['image_path'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('news.show', $news)->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(NewsPost $news)
    {
        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }

        $news->delete();

        return redirect()->route('news.index')->with('success', 'Berita berhasil dihapus.');
    }

    public function approveComment(NewsComment $comment)
    {
        $comment->update(['status' => 'approved']);

        return back()->with('success', 'Komentar disetujui.');
    }

    public function rejectComment(NewsComment $comment)
    {
        $comment->update(['status' => 'rejected']);

        return back()->with('success', 'Komentar ditolak.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'news_category_id' => ['required', 'exists:news_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
            'image' => ['nullable', 'image', 'max:3072'],
        ]);
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (NewsPost::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
