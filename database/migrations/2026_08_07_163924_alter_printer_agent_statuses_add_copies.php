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
        Schema::table('printer_agent_statuses', function (Blueprint $table) {
            $table->unsignedTinyInteger('copies')->default(1)->after('is_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('printer_agent_statuses', function (Blueprint $table) {
            $table->dropColumn('copies');
        });
    }
};
