<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('property_id');
            $table->uuid('landlord_id');
            $table->uuid('tenant_id')->nullable();  // Tenant can be null initially
            $table->string('name');
            $table->integer('no_of_rooms');
            $table->integer('no_of_baths');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['vacant', 'occupied']);
            $table->timestamps();

            // Foreign keys
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('landlord_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('set null');  // If tenant deleted, keep unit intact
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
}
