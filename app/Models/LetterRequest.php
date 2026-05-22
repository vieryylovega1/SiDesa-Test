<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'applicant_name',
        'letter_type',
        'letter_code',
        'purpose',
        'phone',
        'status',
        'letter_number',
        'verification_code',
        'digital_signature',
        'signed_by',
        'signed_at',
        'template_data',
        'requested_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'date',
            'signed_at' => 'datetime',
            'template_data' => 'array',
        ];
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
