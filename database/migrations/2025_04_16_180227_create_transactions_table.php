<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('checkout_id')->unique();
                $table->string('status');
                $table->float('amount');
                $table->string('currency');
                $table->json('data')->nullable();
                $table->json('trackable_data');
                $table->string('brand');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
