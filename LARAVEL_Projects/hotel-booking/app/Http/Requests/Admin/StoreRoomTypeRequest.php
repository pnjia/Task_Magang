<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomTypeRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan melakukan request ini.
     * Kita return true karena pengecekan role 'admin' sudah ditangani oleh Middleware IsAdmin kita.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi yang sangat ketat (Security First).
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // Slug harus unik di tabel room_types kolom slug
            'slug' => ['required', 'string', 'max:255', 'unique:room_types,slug'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ];
    }
}