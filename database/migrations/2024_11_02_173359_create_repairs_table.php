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
        Schema::create('repairs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ref', 50)->nullable();
            $table->uuid('property_id');
            $table->uuid('unit_id');
            $table->uuid('tenant_id');
            $table->string('subject', 100);
            $table->text('description');
            $table->text('comment')->nullable();
            $table->string('status', 20)->default('pending')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
