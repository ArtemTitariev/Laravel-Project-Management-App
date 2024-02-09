<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PositionsTableSeeder::class);
        $this->call(UserRolesTableSeeder::class);
        $this->call(TeamsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TaskCategoriesTableSeeder::class);
        $this->call(TaskStatusesTableSeeder::class);
        $this->call(ProjectCategoriesTableSeeder::class);
        $this->call(ProjectStatusesTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(TeamMembersTableSeeder::class);
        $this->call(TeamProjectsTableSeeder::class);
        $this->call(MediaTableSeeder::class);
    }
}
