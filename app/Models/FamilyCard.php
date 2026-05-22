<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FamilyCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'head_name',
        'address',
        'rt',
        'rw',
        'hamlet',
    ];

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class, 'kk', 'number');
    }
}
