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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('activity_id');
            $table->uuid('unit_id');
            $table->uuid('landlord_id');
            $table->uuid('tenant_id');
            $table->string('first_name', 30);
            $table->string('last_name', 25);
            $table->string('phone', 20)->nullable();
            $table->string('email', 50);
            $table->double('amount');
            $table->string('ref', 20);
            $table->string('invoice_no', 20)->nullable();
            $table->string('status', 15)->default('pending');
            $table->date('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
