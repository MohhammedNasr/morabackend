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
        Schema::table('suppliers', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('suppliers', 'business_name')) {
                $table->string('business_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('suppliers', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'country')) {
                $table->string('country')->default('Saudi Arabia')->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'commercial_registration')) {
                $table->string('commercial_registration')->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'account_number')) {
                $table->string('account_number')->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'beneficiary_name')) {
                $table->string('beneficiary_name')->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'settlement_frequency')) {
                $table->enum('settlement_frequency', ['weekly', 'bi-weekly', 'monthly'])->default('monthly');
            }
            if (!Schema::hasColumn('suppliers', 'settlement_day')) {
                $table->integer('settlement_day')->default(1);
            }
            if (!Schema::hasColumn('suppliers', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('suppliers', 'notify_on_transaction')) {
                $table->boolean('notify_on_transaction')->default(true);
            }
            if (!Schema::hasColumn('suppliers', 'notify_on_settlement')) {
                $table->boolean('notify_on_settlement')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn([
                'business_name',
                'city',
                'country',
                'commercial_registration',
                'account_number',
                'beneficiary_name',
                'settlement_frequency',
                'settlement_day',
                'verified_by',
                'notify_on_transaction',
                'notify_on_settlement',
            ]);
        });
    }
};
