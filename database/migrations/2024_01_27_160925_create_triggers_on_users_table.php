<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("CREATE TRIGGER `tr_Before_Insert_User_Full_Name` BEFORE INSERT ON `users` FOR EACH ROW 
            SET NEW.full_name = 
            CONCAT(NEW.name, ' ', NEW.second_name);");

        DB::unprepared("CREATE TRIGGER `tr_Before_Update_User_Full_Name` BEFORE UPDATE ON `users` FOR EACH ROW 
            SET NEW.full_name = 
            CONCAT(NEW.name, ' ', NEW.second_name);");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER tr_Before_Insert_User_Full_Name;');
        DB::unprepared('DROP TRIGGER tr_Before_Update_User_Full_Name;');
    }
};
