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
        Schema::create('store_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_primary')->default(false); // To identify the main store owner
            $table->timestamps();

            // Ensure a user can't be added to the same store multiple times
            $table->unique(['store_id', 'user_id']);

            // Ensure only one primary owner per store
            $table->unique(['store_id', 'is_primary'], 'unique_primary_owner');
        });

        // Move existing store-user relationships to the pivot table
        Schema::table('stores', function (Blueprint $table) {
            // First, create pivot records for existing relationships
            DB::statement('
                INSERT INTO store_users (store_id, user_id, is_primary, created_at, updated_at)
                SELECT id, user_id, true, NOW(), NOW()
                FROM stores
                WHERE user_id IS NOT NULL
            ');

            // Then remove the user_id column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the user_id column
        Schema::table('stores', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained();
        });

        // Move primary owners back to stores table
        DB::statement('
            UPDATE stores s
            INNER JOIN store_users su ON s.id = su.store_id AND su.is_primary = true
            SET s.user_id = su.user_id
        ');

        Schema::dropIfExists('store_users');
    }
};
