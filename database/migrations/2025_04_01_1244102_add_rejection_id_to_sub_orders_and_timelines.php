<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('sub_orders', 'rejection_id')) {
            Schema::table('sub_orders', function (Blueprint $table) {
                $table->unsignedBigInteger('rejection_id')->nullable()->after('rejection_reason');
                $table->foreign('rejection_id')
                      ->references('id')
                      ->on('rejection_reasons')
                      ->onDelete('set null');
            });
        }

        if (!Schema::hasColumn('sub_order_timelines', 'rejection_id')) {
            Schema::table('sub_order_timelines', function (Blueprint $table) {
                $table->unsignedBigInteger('rejection_id')->nullable()->after('notes');
                $table->foreign('rejection_id')
                      ->references('id')
                      ->on('rejection_reasons')
                      ->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->dropForeign(['rejection_id']);
            $table->dropColumn('rejection_id');
        });

        Schema::table('sub_order_timelines', function (Blueprint $table) {
            $table->dropForeign(['rejection_id']);
            $table->dropColumn('rejection_id');
        });
    }
};
