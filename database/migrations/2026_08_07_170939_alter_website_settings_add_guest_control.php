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
        Schema::table('website_settings', function (Blueprint $table) {
            // Defaults to on (1) so shipping this doesn't change existing
            // guest checkout behaviour until an admin deliberately turns it
            // off on Setting Manage > Website.
            $table->boolean('guest_control')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn('guest_control');
        });
    }
};
