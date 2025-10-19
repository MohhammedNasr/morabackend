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
        Schema::table('notifications', function (Blueprint $table) {
            // Add user_id column as foreign key nullable only if it doesn't exist
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }

            // Modify user_type column to be nullable
            $table->string('user_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop user_id column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Modify user_type column to be not nullable
            $table->string('user_type')->nullable(false)->change();
        });
    }
};
