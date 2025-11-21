<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('post_category')->delete();
        
        \DB::table('post_category')->insert(array (
            0 => 
            array (
                'post_id' => 1,
                'category_id' => 2,
            ),
        ));
        
        
    }
}