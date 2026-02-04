<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class TenantContext
{
    const SESSION_KEY = 'active_tenant_id';

    /**
     * Set Tenant ID yang sedang aktif ke sesi sementara.
     */
    public static function setId(string $uuid): void
    {
        Session::put(self::SESSION_KEY, $uuid);
    }

    /**
     * Ambil Tenant ID yang sedang aktif.
     */
    public static function getId(): ?string
    {
        return Session::get(self::SESSION_KEY);
    }

    /**
     * Hapus sesi tenant (misal saat logout).
     */
    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}