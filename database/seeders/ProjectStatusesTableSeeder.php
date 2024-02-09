<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('project_statuses')->delete();
        
        DB::table('project_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Finished',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'In-Progress',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}