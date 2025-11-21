<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostMetasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('post_metas')->delete();
        
        \DB::table('post_metas')->insert(array (
            0 => 
            array (
                'id' => 1,
                'post_id' => 1,
                'key' => 'title_id',
                'value' => '',
                'type' => 'text',
                'section' => 'hero',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            1 => 
            array (
                'id' => 2,
                'post_id' => 1,
                'key' => 'title_en',
                'value' => '',
                'type' => 'text',
                'section' => 'hero',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            2 => 
            array (
                'id' => 3,
                'post_id' => 1,
                'key' => 'sub_title_id',
                'value' => '',
                'type' => 'text',
                'section' => 'hero',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            3 => 
            array (
                'id' => 4,
                'post_id' => 1,
                'key' => 'sub_title_en',
                'value' => '',
                'type' => 'text',
                'section' => 'hero',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            4 => 
            array (
                'id' => 5,
                'post_id' => 1,
                'key' => 'banner_image_desktop',
                'value' => '',
                'type' => 'text',
                'section' => 'hero',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            5 => 
            array (
                'id' => 6,
                'post_id' => 1,
                'key' => 'banner_image_mobile',
                'value' => '',
                'type' => 'text',
                'section' => 'hero',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            6 => 
            array (
                'id' => 7,
                'post_id' => 1,
                'key' => 'logo',
                'value' => '',
                'type' => 'text',
                'section' => 'festival',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            7 => 
            array (
                'id' => 8,
                'post_id' => 1,
                'key' => 'description_id',
                'value' => '',
                'type' => 'text',
                'section' => 'festival',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            8 => 
            array (
                'id' => 9,
                'post_id' => 1,
                'key' => 'description_en',
                'value' => '',
                'type' => 'text',
                'section' => 'festival',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            9 => 
            array (
                'id' => 10,
                'post_id' => 1,
                'key' => 'link_label_id',
                'value' => '',
                'type' => 'text',
                'section' => 'festival',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            10 => 
            array (
                'id' => 11,
                'post_id' => 1,
                'key' => 'link_label_en',
                'value' => '',
                'type' => 'text',
                'section' => 'festival',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            11 => 
            array (
                'id' => 12,
                'post_id' => 1,
                'key' => 'link_url',
                'value' => '',
                'type' => 'text',
                'section' => 'festival',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
            12 => 
            array (
                'id' => 13,
                'post_id' => 1,
                'key' => 'items',
                'value' => '[]',
                'type' => 'repeater',
                'section' => 'gallery',
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
        ));
        
        
    }
}