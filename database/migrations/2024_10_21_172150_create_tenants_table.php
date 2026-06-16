<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('phone', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->text('work_address')->nullable();
            $table->string('occupation', 50)->nullable();
            $table->string('g_first_name', 50);
            $table->string('g_last_name', 50);
            $table->string('g_phone', 20)->nullable();
            $table->string('g_email', 50)->nullable();
            $table->text('g_address')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
