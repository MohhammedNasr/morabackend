<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('previous_sub_total', 10, 2)->nullable();
            $table->decimal('previous_total_amount', 10, 2)->nullable();
            $table->text('modification_notes')->nullable();
        });

        Schema::table('sub_orders', function (Blueprint $table) {
            $table->decimal('previous_amount', 10, 2)->nullable();
            $table->text('modification_notes')->nullable();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('previous_unit_price', 10, 2)->nullable();
            $table->decimal('previous_total_price', 10, 2)->nullable();
            $table->text('modification_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'previous_sub_total',
                'previous_total_amount',
                'modification_notes'
            ]);
        });

        Schema::table('sub_orders', function (Blueprint $table) {
            $table->dropColumn([
                'previous_amount',
                'modification_notes'
            ]);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'previous_unit_price',
                'previous_total_price',
                'modification_notes'
            ]);
        });
    }
};
