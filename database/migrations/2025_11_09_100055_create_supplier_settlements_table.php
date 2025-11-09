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
        Schema::create('supplier_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            
            $table->string('settlement_reference')->unique();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_amount', 15, 2);
            $table->integer('transaction_count')->default(0);
            
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->date('scheduled_payment_date');
            $table->timestamp('actual_payment_date')->nullable();
            
            // Payment details
            $table->string('payment_reference')->nullable();
            $table->string('payment_method')->nullable()->comment('Bank transfer, etc.');
            $table->string('bank_account')->nullable();
            $table->text('payment_notes')->nullable();
            
            // Processing
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['supplier_id', 'status', 'scheduled_payment_date'], 'supplier_settlements_lookup_idx');
            $table->index('settlement_reference', 'settlement_ref_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_settlements');
    }
};
