<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_number',
        'reporter_name',
        'phone',
        'address',
        'category',
        'title',
        'description',
        'photo_path',
        'status',
        'admin_reply',
        'replied_by',
        'replied_at',
    ];

    protected function casts(): array
    {
        return ['replied_at' => 'datetime'];
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}
