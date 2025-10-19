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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('id_number')->nullable()->unique()->after('bank_account');
            $table->string('iban_number')->nullable()->unique()->after('id_number');
            $table->string('bank_name')->nullable()->after('iban_number');
            $table->string('account_owner_name')->nullable()->after('bank_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['id_number', 'iban_number', 'bank_name', 'account_owner_name']);
        });
    }
};
