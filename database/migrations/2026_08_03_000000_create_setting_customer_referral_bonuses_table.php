<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('setting_customer_referral_bonuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_lvl')->nullable();
            $table->integer('target_orders')->default(0);
            $table->decimal('amount', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('setting_customer_referral_bonuses');
    }
};
