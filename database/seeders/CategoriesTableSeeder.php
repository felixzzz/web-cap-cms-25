<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 2,
                'type' => 'news',
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'This is an example category for News',
                'parent' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2023-08-07 09:32:07',
                'updated_at' => '2023-08-07 09:32:07',
            ),
        ));
        
        
    }
}