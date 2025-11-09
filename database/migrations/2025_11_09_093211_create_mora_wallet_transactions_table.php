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
        Schema::create('mora_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mora_wallet_id')->constrained('mora_wallet')->onDelete('cascade');
            $table->enum('type', ['credit', 'debit'])->comment('credit = money in, debit = money out');
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('reference_number')->unique()->comment('Unique transaction reference');
            $table->string('transaction_type')->comment('balance_approval, loan_repayment, manual_adjustment');
            $table->text('description')->nullable();
            
            // Related entities
            $table->foreignId('balance_request_id')->nullable()->constrained('branch_balance_requests')->onDelete('set null');
            $table->foreignId('initiated_by')->nullable()->constrained('users')->onDelete('set null')->comment('User who initiated transaction');
            
            // Metadata
            $table->json('metadata')->nullable()->comment('Additional transaction data');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['type', 'created_at']);
            $table->index('reference_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mora_wallet_transactions');
    }
};
