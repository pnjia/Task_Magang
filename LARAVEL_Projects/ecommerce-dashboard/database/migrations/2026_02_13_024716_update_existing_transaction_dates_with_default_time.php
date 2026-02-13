<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing transactions that have 00:00:00 time
        // Set them to 09:00:00 WIB (02:00:00 UTC) as a reasonable default business hour
        DB::table('transactions')
            ->whereRaw('TIME(transaction_date) = "00:00:00"')
            ->update([
                'transaction_date' => DB::raw("DATE_ADD(transaction_date, INTERVAL 2 HOUR)")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reliably reverse this without tracking which records were modified
        // Leave as-is
    }
};
