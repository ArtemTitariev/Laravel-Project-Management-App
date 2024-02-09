<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('media')->delete();
        
        DB::table('media')->insert(array (
            0 => 
            array (
                'id' => 15,
                'model_type' => 'App\\Models\\User',
                'model_id' => 2,
                'uuid' => '1e4e1255-923a-4468-a4d4-4d63cbe73ebb',
                'collection_name' => 'default',
                'name' => '11',
                'file_name' => '01HP6S9AV817YBRHH5X4ZC06EP.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 91530,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2024-02-09 11:15:47',
                'updated_at' => '2024-02-09 11:15:47',
            ),
            1 => 
            array (
                'id' => 17,
                'model_type' => 'App\\Models\\User',
                'model_id' => 1,
                'uuid' => 'a5073d0a-9205-4e8a-a0d8-7e91a1cdb984',
                'collection_name' => 'default',
                'name' => 'profile-user-icon',
                'file_name' => '01HP6SHBFZF1R16NAQNP9FWF5Q.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 8812,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2024-02-09 11:20:10',
                'updated_at' => '2024-02-09 11:20:10',
            ),
        ));
        
        
    }
}