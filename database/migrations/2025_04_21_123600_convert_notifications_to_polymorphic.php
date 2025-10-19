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
            // Remove foreign key first
            $table->dropForeign(['user_id']);

            // Rename column
            $table->renameColumn('user_id', 'notifiable_id');

            // Add polymorphic type column
            $table->string('notifiable_type')->after('notifiable_id');

            // Add index for better performance
            $table->index(['notifiable_id', 'notifiable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Remove index first
            $table->dropIndex(['notifiable_id', 'notifiable_type']);

            // Remove polymorphic type column
            $table->dropColumn('notifiable_type');

            // Rename back to user_id
            $table->renameColumn('notifiable_id', 'user_id');

            // Add foreign key back
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
