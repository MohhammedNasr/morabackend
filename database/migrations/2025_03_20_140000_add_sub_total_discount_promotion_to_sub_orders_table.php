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
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->decimal('sub_total', 10, 2)->after('total_amount');
            $table->decimal('discount', 10, 2)->default(0)->after('sub_total');
            $table->foreignId('promotion_id')->nullable()->after('discount')->constrained('promotions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->dropForeign(['promotion_id']);
            $table->dropColumn(['sub_total', 'discount', 'promotion_id']);
        });
    }
};
