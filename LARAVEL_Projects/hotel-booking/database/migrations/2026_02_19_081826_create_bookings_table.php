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
        Schema::create('bookings', function (Blueprint $table) {
            // Kita gunakan UUID agar aman dijadikan Order ID saat dikirim ke Midtrans
            $table->uuid('id')->primary();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();

            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_price', 12, 2);

            // Status pemesanan
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');

            // Disiapkan untuk Midtrans nanti
            $table->string('snap_token')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};