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
        Schema::table('users', function (Blueprint $table) {
            $table->string('authentik_id')->nullable();
            $table->string('github_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('discord_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('authentik_id');
            $table->dropColumn('github_id');
            $table->dropColumn('google_id');
            $table->dropColumn('discord_id');
        });
    }
};
