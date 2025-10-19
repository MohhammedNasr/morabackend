<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{Schema, DB};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add store_id to wallets table
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->change();
        });
        // Update order_payments status enum
        DB::statement("ALTER TABLE order_payments MODIFY COLUMN status ENUM('pending', 'paid', 'due_to_pay')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert order_payments status enum
        DB::statement("ALTER TABLE order_payments MODIFY COLUMN status ENUM('pending', 'paid')");
    }
};
