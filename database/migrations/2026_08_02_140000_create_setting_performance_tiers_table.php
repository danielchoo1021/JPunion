<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_performance_tiers', function (Blueprint $table) {
            $table->id();
            $table->integer('target')->default(0); // min direct downline agent count
            $table->decimal('amount', 8, 2)->default(0); // additional % added on top of the agent's base Performance Reward %
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_performance_tiers');
    }
};
