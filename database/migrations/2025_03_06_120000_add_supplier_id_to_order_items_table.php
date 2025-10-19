<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('order_items', 'supplier_id')) {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('supplier_id')
                ->after('product_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }
    }

    public function down()
    {
        if (Schema::hasColumn('order_items', 'supplier_id')) {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
    }
};
