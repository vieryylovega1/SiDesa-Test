<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsCategoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:news_categories,name'],
        ]);

        NewsCategory::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return back()->with('success', 'Kategori berita berhasil ditambahkan.');
    }
}
