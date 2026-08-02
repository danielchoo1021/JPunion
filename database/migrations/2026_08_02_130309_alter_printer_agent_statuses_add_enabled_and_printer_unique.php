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
            $table->dropUnique(['document_type']);
            $table->unique('printer_name');
            $table->boolean('is_enabled')->default(true)->after('document_type');
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
            $table->dropUnique(['printer_name']);
            $table->unique('document_type');
            $table->dropColumn('is_enabled');
        });
    }
};
