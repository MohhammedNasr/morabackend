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
        // Set is_active default to 0 for users and suppliers
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(0)->change();
            $table->boolean('is_verified')->default(0)->change();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->boolean('is_active')->default(0)->change();
            $table->boolean('is_verified')->default(0)->change();
        });

        // Add is_verified to representatives with default 1
        Schema::table('representatives', function (Blueprint $table) {
            $table->boolean('is_verified')->default(1)->after('role_id');
            $table->boolean('is_active')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert is_active defaults
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(null)->change();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->boolean('is_active')->default(null)->change();
        });

        // Remove is_verified from representatives
        Schema::table('representatives', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
};
