<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('stores', 'logo')) {
                $table->string('logo')->nullable()->after('description');
            }
            if (!Schema::hasColumn('stores', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('logo');
            }
            if (!Schema::hasColumn('stores', 'testimonial')) {
                $table->text('testimonial')->nullable()->after('is_featured');
            }
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['description', 'logo', 'is_featured', 'testimonial']);
        });
    }
};
