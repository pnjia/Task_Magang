<?php

namespace App\Models;

use App\Models\Scopes\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    use HasUuids, BelongsToTenant;

    protected $guarded = ['id'];

    protected $fillable = [
        'tenant_id',
        'user_id',
        'invoice_code',
        'transaction_date',
        'total_amount',
        'payment_amount',
        'change_amount'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
