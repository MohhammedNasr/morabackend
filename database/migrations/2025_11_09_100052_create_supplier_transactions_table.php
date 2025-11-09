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
        Schema::create('supplier_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            
            $table->string('transaction_reference')->unique();
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->text('items')->nullable()->comment('JSON array of items purchased');
            
            $table->enum('status', ['pending_settlement', 'processing', 'settled', 'cancelled'])->default('pending_settlement');
            $table->timestamp('transaction_date');
            $table->timestamp('settlement_date')->nullable();
            $table->foreignId('settlement_id')->nullable()->constrained('supplier_settlements')->onDelete('set null');
            
            // Metadata
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['supplier_id', 'status', 'transaction_date'], 'supplier_txn_lookup_idx');
            $table->index(['store_id', 'transaction_date'], 'store_txn_date_idx');
            $table->index('transaction_reference', 'txn_ref_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_transactions');
    }
};
