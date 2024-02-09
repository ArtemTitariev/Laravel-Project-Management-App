<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('positions')->delete();

        DB::table('positions')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Project manager',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Developer',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
            2 =>
            array(
                'id' => 4,
                'name' => 'Designer',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
            3 =>
            array(
                'id' => 5,
                'name' => 'Tester',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => NULL,
            ),
        ));
    }
}
