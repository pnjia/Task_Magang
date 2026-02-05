<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Tabel Transaksi (Header)
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');

            // Perbaikan 1: Kolom User (Kasir)
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('invoice_code');

            // Perbaikan 2: Kolom Tanggal
            $table->date('transaction_date');

            $table->decimal('total_amount', 15, 2);
            $table->decimal('payment_amount', 15, 2);
            $table->decimal('change_amount', 15, 2);

            $table->timestamps();
        });

        // 2. Tabel Detail Transaksi (Item Belanjaan)
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');

            // Perbaikan 3: Kolom Produk (Penyebab error terakhir Anda)
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');
    }
};