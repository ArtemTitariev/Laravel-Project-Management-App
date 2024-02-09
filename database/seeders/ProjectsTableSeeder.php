<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('projects')->delete();
        
        DB::table('projects')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'test web project',
                'pm_id' => 2,
                'category_id' => 1,
                'status_id' => 2,
                'start_date' => '2024-01-20',
                'finish_date' => '2024-08-01',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'test android project',
                'pm_id' => 2,
                'category_id' => 2,
                'status_id' => 2,
                'start_date' => '2024-01-25',
                'finish_date' => '2024-06-01',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}