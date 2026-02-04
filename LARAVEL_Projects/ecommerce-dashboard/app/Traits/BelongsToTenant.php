<?php

namespace App\Traits;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public static function bootBelongsToTenant()
    {
        // 1. GLOBAL SCOPE: Otomatis memfilter query SELECT
        // Setiap kali kita panggil Model::all(), Laravel akan otomatis
        // menambahkan "WHERE tenant_id = 'uuid-toko-aktif'"
        if (TenantContext::getId()) {
            static::addGlobalScope('tenant', function (Builder $builder) {
                $builder->where('tenant_id', TenantContext::getId());
            });
        }

        // 2. CREATING EVENT: Otomatis mengisi tenant_id saat INSERT
        // Saat kita simpan data baru ($product->save()), 
        // kita tidak perlu isi tenant_id manual. Kode ini yang isikan.
        static::creating(function ($model) {
            if (!$model->tenant_id && TenantContext::getId()) {
                $model->tenant_id = TenantContext::getId();
            }
        });
    }

    /**
     * Relasi ke model Tenant (Akan kita buat Model-nya di Fase 4)
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}