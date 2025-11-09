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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('contact_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('commercial_registration')->nullable();
            $table->string('tax_id')->nullable();
            
            // Bank details
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('beneficiary_name')->nullable();
            
            // Settlement preferences
            $table->enum('settlement_frequency', ['weekly', 'bi-weekly', 'monthly'])->default('monthly');
            $table->integer('settlement_day')->default(1)->comment('Day of month/week for settlement');
            
            // Business details
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Saudi Arabia');
            
            // Status & verification
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Notification preferences
            $table->boolean('notify_on_transaction')->default(true);
            $table->boolean('notify_on_settlement')->default(true);
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['email', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
