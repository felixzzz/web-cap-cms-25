<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SidebarMenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('sidebar_menus')->delete();

        \DB::table('sidebar_menus')->insert(array (
            0 =>
            array (
                'id' => '30e3e2e7-6524-4202-9271-40e325045497',
                'title' => 'News',
                'parent_id' => NULL,
                'url' => '#',
                'icon' => 'fas fa-newspaper',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2023-07-31 14:43:32',
                'updated_at' => '2023-07-31 16:54:50',
            ),
            1 =>
            array (
                'id' => '4106b612-9cda-4fcb-816b-6802594f61c3',
                'title' => 'Page',
                'parent_id' => NULL,
                'url' => 'https://antiplayground.test/antiadmin/page',
                'icon' => 'fas fa-file',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2023-07-31 14:43:32',
                'updated_at' => '2023-07-31 16:54:50',
            ),
            2 =>
            array (
                'id' => '456605d5-65ba-4106-aa85-69b81028fadb',
                'title' => 'Tag',
                'parent_id' => '30e3e2e7-6524-4202-9271-40e325045497',
                'url' => 'https://antiplayground.test/antiadmin/tags',
                'icon' => '',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2023-07-31 14:43:32',
                'updated_at' => '2023-07-31 16:54:50',
            ),
            3 =>
            array (
                'id' => '69a63b1d-c5ad-4d6b-b99a-9278bcb9b17d',
                'title' => 'All News',
                'parent_id' => '30e3e2e7-6524-4202-9271-40e325045497',
                'url' => 'https://antiplayground.test/antiadmin/posts/news',
                'icon' => '',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2023-07-31 14:43:32',
                'updated_at' => '2023-07-31 16:54:50',
            ),
            4 =>
            array (
                'id' => 'd24ff4a6-a001-4cdb-b401-5a516af5e4d0',
                'title' => 'Dashboard',
                'parent_id' => NULL,
                'url' => 'https://antiplayground.test/antiadmin/dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2023-07-31 14:43:32',
                'updated_at' => '2023-07-31 16:54:50',
            ),
            5 =>
            array (
                'id' => 'db80fca1-4837-454b-b6fe-246a83e0f90a',
                'title' => 'Categories',
                'parent_id' => '30e3e2e7-6524-4202-9271-40e325045497',
                'url' => 'https://antiplayground.test/antiadmin/categories/news',
                'icon' => NULL,
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2023-07-31 14:43:32',
                'updated_at' => '2023-08-01 11:45:40',
            ),
        ));


    }
}
