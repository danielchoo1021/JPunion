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
        Schema::create('printer_agent_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('document_type', 30)->unique(); // invoice_a4 | packing_label
            $table->string('printer_name');
            $table->string('port_name')->nullable();
            $table->string('windows_status')->nullable(); // raw Get-Printer status text
            $table->boolean('is_ready')->default(false);
            $table->timestamp('reported_at')->nullable();
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
        Schema::dropIfExists('printer_agent_statuses');
    }
};
