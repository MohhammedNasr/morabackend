<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_timelines', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('created_by');
            $table->enum('user_type', ['store-owner', 'representative'])
                ->nullable()
                ->after('created_by');
        });
    }

    public function down()
    {
        Schema::table('order_timelines', function (Blueprint $table) {
            $table->dropColumn(['user_type','user_id']);
        });
    }
};
