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
            if (!Schema::hasColumn('store_branches', 'longitude')) {
                $table->string('longitude')->nullable();
            }
            if (!Schema::hasColumn('store_branches', 'latitude')) {
                $table->string('latitude')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_branches', function (Blueprint $table) {
            if (Schema::hasColumn('store_branches', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('store_branches', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};
