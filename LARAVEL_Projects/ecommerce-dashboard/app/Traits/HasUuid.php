<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    // Event 'booting' trait ini akan jalan otomatis
    protected static function bootHasUuid()
    {
        // Saat model sedang dibuat (creating)...
        static::creating(function ($model) {
            // Jika kolom primary key (id) masih kosong...
            if (empty($model->{$model->getKeyName()})) {
                // Isi dengan UUID baru
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Memberi tahu Laravel bahwa Primary Key TIDAK Auto Increment
    public function getIncrementing()
    {
        return false;
    }

    // Memberi tahu Laravel bahwa tipe datanya adalah String
    public function getKeyType()
    {
        return 'string';
    }
}