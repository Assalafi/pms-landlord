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
        Schema::create('receipts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('invoice_id');
            $table->uuid('tenant_id');
            $table->uuid('landlord_id');
            $table->string('first_name', 30)->nullable();
            $table->string('last_name', 25)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->double('amount');
            $table->string('ref', 50)->nullable();
            $table->string('order_id', 50)->nullable();
            $table->string('receipt_no', 50)->nullable();
            $table->string('status', 15)->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
