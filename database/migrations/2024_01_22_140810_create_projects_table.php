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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->unsignedBigInteger('pm_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('status_id');

            $table->date('start_date');
            $table->date('finish_date')->nullable();

            $table->timestamps();

            $table->foreign('pm_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('category_id')
                ->references('id')->on('project_categories')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('status_id')
                ->references('id')->on('project_statuses')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
