<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAssistanceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_assistance_recipient_id',
        'distributed_at',
        'period',
        'amount',
        'status',
        'description',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'distributed_at' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(SocialAssistanceRecipient::class, 'social_assistance_recipient_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
