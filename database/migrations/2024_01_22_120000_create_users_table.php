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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('second_name');
            $table->string('email')->unique();
            $table->string('password');

            $table->unsignedBigInteger('position_id');
            $table->string('avatar')->nullable();

            $table->unsignedBigInteger('user_role_id');

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('position_id')
                ->references('id')->on('positions')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('user_role_id')
                ->references('id')->on('user_roles')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
