<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'kk',
        'family_relationship',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'occupation',
        'education',
        'photo_path',
        'marital_status',
        'address',
        'rt',
        'rw',
        'hamlet',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function letterRequests(): HasMany
    {
        return $this->hasMany(LetterRequest::class);
    }

    public function familyCard(): BelongsTo
    {
        return $this->belongsTo(FamilyCard::class, 'kk', 'number');
    }
}
