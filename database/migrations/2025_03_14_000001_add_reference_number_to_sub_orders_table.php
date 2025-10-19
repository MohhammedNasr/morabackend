<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceNumberToSubOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->after('modification_notes');
        });
    }

    public function down()
    {
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });
    }
}
