<?php

namespace App\Models;

// 1. Ganti import ini (Gunakan bawaan Laravel)
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    // 2. Pasang Trait HasUuids bawaan
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'slug', 'address'];

    // Relasi-relasi di bawah ini sudah benar, biarkan saja
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}