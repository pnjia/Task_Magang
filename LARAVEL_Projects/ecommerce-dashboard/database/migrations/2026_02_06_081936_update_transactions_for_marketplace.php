<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // 1. Tambah Kolom Data Pembeli Online
            $table->string('customer_name')->nullable()->after('invoice_code');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('customer_address')->nullable()->after('customer_phone');

            // 2. Tambah Status Pesanan
            $table->enum('status', ['unpaid', 'paid', 'processing', 'shipped', 'completed', 'cancelled'])
                ->default('unpaid')
                ->after('change_amount');

            // 3. Ubah Kolom Kasir jadi "Boleh Kosong" (Nullable)
            // Karena saat checkout online, pembeli belum bayar & belum ada kembalian
            $table->uuid('user_id')->nullable()->change();
            $table->decimal('payment_amount', 15, 2)->nullable()->change();
            $table->decimal('change_amount', 15, 2)->nullable()->change();

            // Opsional: Jika transaction_date ingin otomatis hari ini
            $table->date('transaction_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'customer_phone', 'customer_address', 'status']);
            // Mengembalikan kolom kasir menjadi wajib (hati-hati jika ada data null)
            // $table->uuid('user_id')->nullable(false)->change(); 
        });
    }
};
