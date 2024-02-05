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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('status_id');

            $table->date('start_date');
            $table->date('finish_date')->nullable();

            $table->timestamps();


            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')
                ->references('id')->on('task_categories')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('employee_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('status_id')
                ->references('id')->on('task_statuses')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
