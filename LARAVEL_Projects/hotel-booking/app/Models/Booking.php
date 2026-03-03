<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory, HasUuids; // Penting agar ID UUID otomatis terisi!
    protected $fillable = ['user_id', 'room_type_id', 'check_in', 'check_out', 'total_price', 'status'];
}