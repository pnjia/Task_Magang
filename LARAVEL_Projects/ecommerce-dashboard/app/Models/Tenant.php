<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    //
    use HasFactory, HasUuid;

    protected $fillable = ['name', 'slug', 'address'];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }
}
