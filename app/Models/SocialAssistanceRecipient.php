<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialAssistanceRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'social_assistance_category_id',
        'status',
        'registered_at',
        'eligibility_note',
        'created_by',
    ];

    protected function casts(): array
    {
        return ['registered_at' => 'date'];
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SocialAssistanceCategory::class, 'social_assistance_category_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(SocialAssistanceHistory::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
