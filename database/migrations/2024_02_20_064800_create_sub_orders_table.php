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
        Schema::create('sub_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained();
            $table->string('status');
            $table->decimal('total_amount', 10, 2);
            $table->integer('products_count');
            $table->timestamps();
            $table->softDeletes();
        });

        // Add store_branch_id to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('store_branch_id')->after('store_id')->constrained('store_branches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['store_branch_id']);
            $table->dropColumn('store_branch_id');
        });

        Schema::dropIfExists('sub_orders');
    }
};
