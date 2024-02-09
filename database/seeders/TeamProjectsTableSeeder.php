<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamProjectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('team_projects')->delete();
        
        DB::table('team_projects')->insert(array (
            0 => 
            array (
                'id' => 1,
                'team_id' => 1,
                'project_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'team_id' => 2,
                'project_id' => 2,
            ),
            2 => 
            array (
                'id' => 3,
                'team_id' => 2,
                'project_id' => 1,
            ),
        ));
        
        
    }
}