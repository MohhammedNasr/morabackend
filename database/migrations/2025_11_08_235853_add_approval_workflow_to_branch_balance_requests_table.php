<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('branch_balance_requests', function (Blueprint $table) {
            // Add request number with unique constraint
            $table->string('request_number', 20)->unique()->nullable()->after('id');
            
            // Employee approval fields
            $table->enum('employee_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('employee_comment')->nullable()->after('employee_status');
            $table->foreignId('employee_reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('employee_comment');
            $table->timestamp('employee_reviewed_at')->nullable()->after('employee_reviewed_by');
            
            // Manager approval fields
            $table->enum('manager_status', ['pending', 'approved', 'rejected'])->default('pending')->after('employee_reviewed_at');
            $table->text('manager_comment')->nullable()->after('manager_status');
            $table->foreignId('manager_reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('manager_comment');
            $table->timestamp('manager_reviewed_at')->nullable()->after('manager_reviewed_by');
            
            // Partner approval fields
            $table->enum('partner_status', ['pending', 'approved', 'rejected'])->default('pending')->after('manager_reviewed_at');
            $table->text('partner_comment')->nullable()->after('partner_status');
            $table->foreignId('partner_reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('partner_comment');
            $table->timestamp('partner_reviewed_at')->nullable()->after('partner_reviewed_by');
        });
        
        // Update existing status enum to include workflow stages
        DB::statement("ALTER TABLE branch_balance_requests MODIFY COLUMN status ENUM('pending', 'employee_review', 'manager_review', 'partner_review', 'approved', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branch_balance_requests', function (Blueprint $table) {
            $table->dropColumn([
                'request_number',
                'employee_status',
                'employee_comment',
                'employee_reviewed_by',
                'employee_reviewed_at',
                'manager_status',
                'manager_comment',
                'manager_reviewed_by',
                'manager_reviewed_at',
                'partner_status',
                'partner_comment',
                'partner_reviewed_by',
                'partner_reviewed_at',
            ]);
        });
        
        // Revert status enum to original
        DB::statement("ALTER TABLE branch_balance_requests MODIFY COLUMN status ENUM('pending', 'under_review', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
