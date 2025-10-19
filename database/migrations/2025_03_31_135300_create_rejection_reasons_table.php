<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('rejection_reasons')) {
            Schema::create('rejection_reasons', function (Blueprint $table) {
                $table->id();
                $table->string('reason_ar');
                $table->string('reason_en');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('rejection_reasons');
    }
};
