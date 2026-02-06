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

    protected $fillable = ['name', 'slug', 'phone'];

    // --- FILTER OTOMATIS: Ubah 08 jadi 62 saat disimpan ---
    public function setPhoneAttribute($value)
    {
        // Hapus karakter aneh (spasi, strip, dll)
        $cleanPhone = preg_replace('/[^0-9]/', '', $value);

        // Jika diawali 08, ganti jadi 628
        if (substr($cleanPhone, 0, 2) === '08') {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $this->attributes['phone'] = $cleanPhone;
    }

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