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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('tax_number')->unique()->nullable();
            $table->enum('type', ['hypermarket', 'supermarket', 'restaurant'])->default('supermarket');
            $table->decimal('credit_balance', 10, 2)->default(0);
            $table->integer('branches_count')->default(1);
            $table->string('id_number')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'tax_number',
                'type',
                'credit_balance',
                'branches_count',
                'id_number',
            ]);
        });
    }
};
