<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- 1. IMPORT INI
use App\Models\Scopes\BelongsToTenant;

class Category extends Model
{
    // 2. PASANG HasFactory DI SINI
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = ['name', 'slug', 'tenant_id'];

    protected $keyType = 'string';

    public $incrementing = false;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}