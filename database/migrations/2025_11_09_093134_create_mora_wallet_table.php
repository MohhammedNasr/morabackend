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
        Schema::create('mora_wallet', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance', 15, 2)->default(0)->comment('Mora company available balance');
            $table->decimal('total_credited', 15, 2)->default(0)->comment('Total money added to wallet');
            $table->decimal('total_debited', 15, 2)->default(0)->comment('Total money deducted from wallet');
            $table->string('currency', 3)->default('SAR');
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Create a single default record for Mora's wallet
        \DB::table('mora_wallet')->insert([
            'balance' => 4000000.00,
            'total_credited' => 4000000.00,
            'total_debited' => 0.00,
            'currency' => 'SAR',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mora_wallet');
    }
};
