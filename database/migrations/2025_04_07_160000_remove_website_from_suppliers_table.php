<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('website')->nullable()->change();
            $table->string('contact_name')->nullable()->change();
            $table->integer('payment_term_days')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('website')->nullable(false)->change();
            $table->string('contact_name')->nullable(false)->change();
            $table->integer('payment_term_days')->nullable(false)->change();
        });
    }
};
