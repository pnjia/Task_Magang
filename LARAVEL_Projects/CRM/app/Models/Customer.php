<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    use HasUuids;
    
    protected $fillable = ['name', 'phone_number', 'email'];

    public function interactions() {
        return $this->hasMany(Interaction::class);
    }
}
