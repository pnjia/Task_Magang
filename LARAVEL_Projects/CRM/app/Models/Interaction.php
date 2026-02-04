<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    //
    use HasUlids;

    protected $guarded = ['id'];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
