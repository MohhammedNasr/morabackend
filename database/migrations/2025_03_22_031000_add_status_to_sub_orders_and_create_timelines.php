<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add status column to sub_orders
        // Schema::table('sub_orders', function (Blueprint $table) {
        //     $table->string('status')->after('is_modified');
        // });

        // Create sub_order_timelines table
        Schema::create('sub_order_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_order_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_order_timelines');

        // Schema::table('sub_orders', function (Blueprint $table) {
        //     $table->dropColumn('status');
        // });
    }
};
