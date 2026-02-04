<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('phone_number')->unique();
            $table->string('email')->unique()->nullable();
            $table->timestamps();
        });

        Schema::create('interactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('type');
            $table->text('notes')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->dateTime('occurred_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
        Schema::dropIfExists('customers');
    }
};
