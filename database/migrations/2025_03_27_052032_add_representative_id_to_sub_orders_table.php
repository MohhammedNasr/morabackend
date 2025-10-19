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
        Schema::disableForeignKeyConstraints();
        Schema::table('sub_orders', function (Blueprint $table) {
    

            $table->unsignedBigInteger('representative_id')->after('supplier_id');
                $table->foreign('representative_id')->references('id')->on('representatives');
        });
        Schema::enableForeignKeyConstraints();

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->dropForeign(['representative_id']);
            $table->dropColumn('representative_id');
        });
    }
};
