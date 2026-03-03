<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'base_price', 'capacity'
    ];

    // Relasi: 1 Tipe Kamar memiliki BANYAK Kamar Fisik (One-to-Many)
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}