<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('representatives', function (Blueprint $table) {
            // Only add columns if they don't exist
            if (!Schema::hasColumn('representatives', 'email')) {
                $table->string('email')->unique()->nullable()->change();
            }
            if (!Schema::hasColumn('representatives', 'password')) {
                $table->string('password');
            }
            if (!Schema::hasColumn('representatives', 'remember_token')) {
                $table->rememberToken();
            }
            if (!Schema::hasColumn('representatives', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('representatives', 'created_at')) {
                $table->timestamps();
            }

            // Add foreign key if it doesn't exist
            if (!Schema::hasColumn('representatives', 'supplier_id')) {
                $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('representatives', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at']);
        });
    }
};
