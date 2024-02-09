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
        Schema::create('team_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('team_id');
            $table->unsignedBiginteger('project_id');

            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->unique(['team_id', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_projects');
    }
};
