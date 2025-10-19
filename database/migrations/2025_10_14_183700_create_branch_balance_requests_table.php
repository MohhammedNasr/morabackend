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
        Schema::create('branch_balance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_branch_id')->constrained('store_branches')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->decimal('requested_balance_limit', 15, 2);
            
            // Business Information
            $table->string('business_type')->nullable();
            $table->integer('years_in_business')->nullable();
            $table->decimal('average_monthly_revenue', 15, 2)->nullable();
            $table->integer('number_of_employees')->nullable();
            $table->text('business_description')->nullable();
            
            // Financial Information
            $table->string('tax_registration_number')->nullable();
            $table->string('commercial_registration_number')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('iban')->nullable();
            
            // Contact Information
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_position')->nullable();
            
            // Documents (JSON array of file paths)
            $table->json('documents')->nullable();
            
            // Request Status
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->decimal('approved_balance_limit', 15, 2)->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_balance_requests');
    }
};
