<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Auth\Models\User;
use Spatie\LaravelPasskeys\Support\Config;

return new class extends Migration
{
    public function up()
    {
        $authenticatableClass = User::class;

        $authenticatableTableName = (new $authenticatableClass)->getTable();

        Schema::create('passkeys', function (Blueprint $table) use ($authenticatableTableName,$authenticatableClass) {
            $table->id();

            $table
                ->foreignIdFor($authenticatableClass, 'authenticatable_id')
                ->constrained(table: $authenticatableTableName, indexName: 'passkeys_authenticatable_fk')
                ->cascadeOnDelete();

            $table->text('name');
            $table->text('credential_id');
            $table->json('data');

            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }
};
