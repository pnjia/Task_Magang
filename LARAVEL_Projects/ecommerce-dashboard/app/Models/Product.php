<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- 1. IMPORT INI
use App\Models\Scopes\BelongsToTenant;

class Product extends Model
{
    // 2. PASANG HasFactory DI SINI
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'stock',
        'description',
        'image',
        'is_active',
        'tenant_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}