<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->foreignId('order_payment_id')
                ->nullable()
                ->constrained('order_payments')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['order_payment_id']);
            $table->dropColumn('order_payment_id');
        });
    }
};
