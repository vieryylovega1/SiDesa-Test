<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillageProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'village_name',
        'district',
        'regency',
        'province',
        'postal_code',
        'address',
        'phone',
        'email',
        'website',
        'head_name',
        'head_nip',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'village_name' => 'Sukamaju',
            'district' => 'Harmoni',
            'regency' => 'Sentosa',
            'province' => 'Jawa Tengah',
            'postal_code' => '55555',
            'address' => 'Jl. Raya Desa Sukamaju No. 01',
            'head_name' => 'H. Suryanto',
        ]);
    }
}
