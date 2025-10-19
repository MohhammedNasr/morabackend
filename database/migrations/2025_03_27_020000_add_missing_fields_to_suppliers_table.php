<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            if (!Schema::hasColumn('suppliers', 'contact_name')) {
                $table->string('contact_name')->after('payment_term_days');
            }
            if (!Schema::hasColumn('suppliers', 'tax_id')) {
                $table->string('tax_id', 50)->after('contact_name');
            }
            if (!Schema::hasColumn('suppliers', 'bank_account')) {
                $table->string('bank_account', 100)->nullable()->after('tax_id');
            }
            if (!Schema::hasColumn('suppliers', 'website')) {
                $table->string('website')->nullable()->after('bank_account');
            }
            if (!Schema::hasColumn('suppliers', 'address')) {
                $table->string('address', 500)->after('website');
            }
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn([
                'contact_name',
                'tax_id',
                'bank_account',
                'website',
                'address'
            ]);
        });
    }
};
