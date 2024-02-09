<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('users')->delete();
        
        DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin_name',
                'second_name' => 'admin_surname',
                'email' => 'admin@gmail.com',
                'password' => '$2y$12$HacY.B/DhnquhWV1b6aj1exglboEGH3r0kTrcG7YGn9.kwFBX5j6q',
                'position_id' => 2,
                'avatar' => NULL,
                'user_role_id' => 1,
                'remember_token' => '',
                'created_at' => '2024-01-23 10:58:48',
                'updated_at' => '2024-02-09 08:24:45',
                'full_name' => 'admin_name admin_surname',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'PM_name',
                'second_name' => 'PM_surname',
                'email' => 'pm@gmail.com',
                'password' => '$2y$12$44C8leuu0yVekyaIkNVPJeGgbbHvkoSknP2XdhLQwkCAs2aImvXnC',
                'position_id' => 1,
                'avatar' => NULL,
                'user_role_id' => 2,
                'remember_token' => NULL,
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => '2024-02-09 08:22:18',
                'full_name' => 'PM_name PM_surname',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'test_dev',
                'second_name' => 'dev_surname',
                'email' => 'dev@gmail.com',
                'password' => '$2y$12$44C8leuu0yVekyaIkNVPJeGgbbHvkoSknP2XdhLQwkCAs2aImvXnC',
                'position_id' => 2,
                'avatar' => NULL,
                'user_role_id' => 2,
                'remember_token' => '',
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => '2024-02-09 08:26:15',
                'full_name' => 'test_dev dev_surname',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'test_designer',
                'second_name' => 'surname',
                'email' => 'des@gmail.com',
                'password' => '$2y$12$fawruS8.z8dOTJk/BcVW4uoWUlcPgPumPVZGo2ROVSd7.55Mf5Cs2',
                'position_id' => 4,
                'avatar' => NULL,
                'user_role_id' => 2,
                'remember_token' => NULL,
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => '2024-02-09 08:26:24',
                'full_name' => 'test_designer surname',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'tester_name',
                'second_name' => 'surname',
                'email' => 'tester@gmail.com',
                'password' => '$2y$12$/GIzOEbONuhntCv0L9R7QOSocvP9hIoEg0cj5GPUdkz/EF.Pdq4qy',
                'position_id' => 5,
                'avatar' => NULL,
                'user_role_id' => 2,
                'remember_token' => NULL,
                'created_at' => '2024-01-23 07:58:48',
                'updated_at' => '2024-02-09 10:16:57',
                'full_name' => 'tester_name surname',
            ),
        ));
        
        
    }
}