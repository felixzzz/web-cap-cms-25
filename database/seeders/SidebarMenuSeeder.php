<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SidebarMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = [
            [
                'title' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'fas fa-tachometer-alt',
                'order' => 1
            ],
            [
                'title' => 'Page',
                'url' => route('admin.page'),
                'icon' => 'fas fa-file',
                'order' => 2
            ],
            [
                'title' => 'Form Submission',
                'url' => route('admin.form'),
                'icon' => 'fas fa-file',
                'order' => 4
            ],
            [
                'title' => 'News',
                'url' => '#',
                'icon' => 'fas fa-newspaper',
                'order' => 3,
                'child' => [
                    [
                        'title' => 'All News',
                        'url' => route('admin.post.index','news'),
                        'icon' => '',
                        'order' => 1
                    ],
                    [
                        'title' => 'Categories',
                        'url' => route('admin.category.index','news'),
                        'icon' => '',
                        'order' => 2
                    ],
                    [
                        'title' => 'Tag',
                        'url' => route('admin.tag.index'),
                        'icon' => '',
                        'order' => 3
                    ],
                ]
            ],
        ];

        foreach ($menu as $value) {
            $children = $value['child'] ?? null;
            unset($value['child']);
            $menu = \App\Domains\Core\Models\SidebarMenu::create($value);
            if ($children) {
                foreach ($children as $child) {
                    $menu->children()->create($child);
                }
            }
        }
    }
}
