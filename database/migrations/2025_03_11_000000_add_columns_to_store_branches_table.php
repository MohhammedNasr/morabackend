<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('store_branches', function (Blueprint $table) {
            if (!Schema::hasColumn('store_branches', 'city_id')) {
                $table->foreignId('city_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('store_branches', 'area_id')) {
                $table->foreignId('area_id')->nullable()->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('store_branches', 'latitude')) {
                $table->decimal('latitude', 10, 8)->default(0);
            }
            if (!Schema::hasColumn('store_branches', 'longitude')) {
                $table->decimal('longitude', 11, 8)->default(0);
            }
            if (!Schema::hasColumn('store_branches', 'flat_number')) {
                $table->string('flat_number')->nullable();
            }
            if (!Schema::hasColumn('store_branches', 'floor_number')) {
                $table->string('floor_number')->nullable();
            }
            if (!Schema::hasColumn('store_branches', 'is_main')) {
                $table->boolean('is_main')->default(false);
            }
            if (!Schema::hasColumn('store_branches', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('store_branches', function (Blueprint $table) {
            if (Schema::hasColumn('store_branches', 'city_id')) {
                $table->dropForeign(['city_id']);
                $table->dropColumn('city_id');
            }
            if (Schema::hasColumn('store_branches', 'area_id')) {
                $table->dropForeign(['area_id']);
                $table->dropColumn('area_id');
            }
            $columnsToDrop = ['latitude', 'longitude', 'flat_number', 'floor_number', 'is_main', 'is_active'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('store_branches', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
