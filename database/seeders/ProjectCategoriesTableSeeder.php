<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('project_categories')->delete();
        
        DB::table('project_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Web',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Android',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}