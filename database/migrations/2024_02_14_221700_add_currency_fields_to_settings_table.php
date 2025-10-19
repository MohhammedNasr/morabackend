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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('currency_name_en')->nullable();
            $table->string('currency_name_ar')->nullable();
            $table->string('currency_symbol_en')->nullable();
            $table->string('currency_symbol_ar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'currency_name_en',
                'currency_name_ar',
                'currency_symbol_en',
                'currency_symbol_ar',
            ]);
        });
    }
};
