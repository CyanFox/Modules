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
        Schema::create('redirect_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('redirect_id')->constrained('redirects')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('permission_id')->nullable()->constrained('permissions')->cascadeOnDelete();
            $table->foreignId('role_id')->nullable()->constrained('roles')->cascadeOnDelete();
            $table->boolean('can_update')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redirect_access');
    }
};
