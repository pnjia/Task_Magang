<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    use HasFactory, HasUuid, BelongsToTenant;

    protected $fillable = ['name', 'slug'];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
