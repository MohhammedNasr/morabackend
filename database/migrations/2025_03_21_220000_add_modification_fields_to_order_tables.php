<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('is_modified')->default(false);
            $table->integer('previous_quantity')->nullable();
        });

        Schema::table('sub_orders', function (Blueprint $table) {
            $table->boolean('is_modified')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['is_modified', 'previous_quantity']);
        });

        Schema::table('sub_orders', function (Blueprint $table) {
            $table->dropColumn('is_modified');
        });
    }
};
