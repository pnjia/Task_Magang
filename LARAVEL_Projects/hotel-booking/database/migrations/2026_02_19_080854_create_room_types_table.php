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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Deluxe Room'
            $table->string('slug')->unique(); // e.g., 'deluxe-room' (Untuk URL SEO ramah)
            $table->text('description')->nullable();
            $table->decimal('base_price', 12, 2); // Presisi uang (Rp), 12 digit total, 2 desimal
            $table->integer('capacity')->default(2); // Kapasitas orang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};