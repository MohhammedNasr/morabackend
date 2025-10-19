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
        Schema::table('store_branches', function (Blueprint $table) {
            if (!Schema::hasColumn('store_branches', 'is_main')) {
                $table->boolean('is_main')->default(false);
            }
            if (!Schema::hasColumn('store_branches', 'is_active')) {
                $table->boolean('is_active')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_branches', function (Blueprint $table) {
            if (Schema::hasColumn('store_branches', 'is_main')) {
                $table->dropColumn('is_main');
            }
            if (Schema::hasColumn('store_branches', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
