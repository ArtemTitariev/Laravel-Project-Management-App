<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamMembersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('team_members')->delete();

        DB::table('team_members')->insert(array(
            0 =>
            array(
                'id' => 2,
                'team_id' => 1,
                'user_id' => 3,
            ),
            1 =>
            array(
                'id' => 3,
                'team_id' => 1,
                'user_id' => 5,
            ),
            2 =>
            array(
                'id' => 5,
                'team_id' => 2,
                'user_id' => 3,
            ),
            3 =>
            array(
                'id' => 6,
                'team_id' => 2,
                'user_id' => 4,
            ),
            4 =>
            array(
                'id' => 7,
                'team_id' => 2,
                'user_id' => 5,
            ),
        ));
    }
}
