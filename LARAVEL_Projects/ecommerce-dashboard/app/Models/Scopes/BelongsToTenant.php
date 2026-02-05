<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        // 1. Global Scope: Filter data saat Query (Read)
        static::addGlobalScope('tenant', function (Builder $builder) {
            // Hanya filter jika user sedang login
            if (Auth::check()) {
                $builder->where('tenant_id', Auth::user()->tenant_id);
            }
        });

        // 2. Observer: Isi tenant_id otomatis saat Create
        static::creating(function (Model $model) {
            // Jika tenant_id belum diisi manual (misal oleh Seeder), ambil dari User Login
            if (!$model->tenant_id && Auth::check()) {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });
    }
}