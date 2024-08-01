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
        Schema::create('telemetry', function (Blueprint $table) {
            $table->id();
            $table->string('instance')->unique();
            $table->integer('modules');
            $table->string('os');
            $table->string('php');
            $table->string('laravel');
            $table->string('db');
            $table->string('timezone');
            $table->string('lang');
            $table->string('template_version');
            $table->string('project_version');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry');
    }
};
