<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('tasks')->delete();
        
        DB::table('tasks')->insert(array (
            0 => 
            array (
                'id' => 3,
                'name' => 'task 1 proj 1',
                'description' => 'some description 1.1',
                'project_id' => 1,
                'category_id' => 1,
                'employee_id' => 3,
                'status_id' => 1,
                'start_date' => '2024-01-16',
                'finish_date' => '2024-01-18',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'task 2 proj 1',
                'description' => 'some description 1.2',
                'project_id' => 1,
                'category_id' => 2,
                'employee_id' => 5,
                'status_id' => 2,
                'start_date' => '2024-01-21',
                'finish_date' => NULL,
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 5,
                'name' => 'task 1 proj 2',
                'description' => 'some description 2.1',
                'project_id' => 2,
                'category_id' => 1,
                'employee_id' => 4,
                'status_id' => 1,
                'start_date' => '2024-01-20',
                'finish_date' => '2024-01-22',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}