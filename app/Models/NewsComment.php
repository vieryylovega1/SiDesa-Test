<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_post_id',
        'name',
        'email',
        'comment',
        'status',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(NewsPost::class, 'news_post_id');
    }
}
