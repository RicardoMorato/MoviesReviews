<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'releaseDate',
        'genre',
        'director',
    ];

    public function reviews(): HasMany {
        return $this->hasMany(Review::class);
    }
}
