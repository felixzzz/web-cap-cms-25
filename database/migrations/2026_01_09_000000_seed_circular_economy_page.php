<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Domains\Core\Models\SidebarMenu;
use App\Domains\Post\Models\Post;
use App\Domains\Post\Models\PostMeta;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Sidebar Menu Logic
        $sustainabilityMenu = SidebarMenu::where('title', 'Sustainability')->first();

        if ($sustainabilityMenu) {
            SidebarMenu::updateOrCreate(
                ['title' => 'Circular Eco'],
                [
                    'parent_id' => $sustainabilityMenu->id,
                    'url' => route('admin.page.template', 'circular-economy-and-partnership'),
                    'icon' => 'fas fa-recycle',
                    'order' => 5,
                    'is_active' => true,
                ]
            );
        } else {
            // Fallback if parent not found
            SidebarMenu::updateOrCreate(
                ['title' => 'Circular Eco'],
                [
                    'url' => route('admin.page.template', 'circular-economy-and-partnership'),
                    'icon' => 'fas fa-recycle',
                    'order' => 5,
                    'is_active' => true,
                ]
            );
        }

        // 2. Post Creation Logic
        $slug = 'circular-economy-and-partnership';
        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            $post = Post::create([
                'title' => 'Circular Economy & Partnership',
                'slug' => $slug,
                'type' => 'page',
                'status' => 'publish',
                'template' => 'circular-economy-and-partnership',
            ]);
        } else {
            $post->update(['template' => 'circular-economy-and-partnership']);
        }

        // 3. Meta Data Logic
        $this->seedMeta($post->id);
    }

    private function seedMeta($postId)
    {
        $metaData = [
            'banner' => [
                'status' => 'active',
                'status_en' => 'active',
                'status_id' => 'active',
                'title_en' => 'Circular Economy & Partnership',
                'title_id' => 'Ekonomi Sirkular & Kemitraan',
                'description_en' => 'Pioneering a sustainable future through closed-loop innovation. We transform waste into valuable resources, fostering a regenerative ecosystem where economic growth meets environmental stewardship.',
                'description_id' => 'Merintis masa depan yang berkelanjutan melalui inovasi siklus tertutup. Kami mengubah limbah menjadi sumber daya berharga, membina ekosistem regeneratif di mana pertumbuhan ekonomi bertemu dengan pengelolaan lingkungan.',
                'image_desktop_en' => 'images/post/temp-card-item.jpg',
                'image_desktop_id' => 'images/post/temp-card-item.jpg',
                'image_mobile_en' => 'images/post/temp-card-item.jpg',
                'image_mobile_id' => 'images/post/temp-card-item.jpg',
                'image_desktop' => 'images/post/temp-card-item.jpg',
                'image_mobile' => 'images/post/temp-card-item.jpg',
            ],
            'content_left_right' => [
                'status_en' => 'active',
                'status_id' => 'active',
                'list_en' => json_encode([
                    [
                        'status' => 'active',
                        'title' => 'Our Vision',
                        'description' => '<p>To be the leading partner in circular economy solutions.</p>',
                        'image' => 'images/post/temp-card-item.jpg',
                        'image_position' => 'left',
                        'cta_label' => 'Learn More',
                        'cta_url' => '#'
                    ]
                ]),
                'list_id' => json_encode([
                    [
                        'status' => 'active',
                        'title' => 'Visi Kami',
                        'description' => '<p>Menjadi mitra utama dalam solusi ekonomi sirkular.</p>',
                        'image' => 'images/post/temp-card-item.jpg',
                        'image_position' => 'left',
                        'cta_label' => 'Pelajari Lebih Lanjut',
                        'cta_url' => '#'
                    ]
                ])
            ],
            'environmental_performance' => [
                'status_en' => 'active',
                'status_id' => 'active',
                'title_en' => 'Environmental Performance for Sustainability',
                'title_id' => 'Kinerja Lingkungan untuk Keberlanjutan',
                'description_en' => '<p>We are committed to reducing our environmental footprint through continuous improvement and innovation.</p>',
                'description_id' => '<p>Kami berkomitmen untuk mengurangi jejak lingkungan kami melalui perbaikan terus-menerus dan inovasi.</p>',
                'numbers_en' => json_encode([
                    [
                        'icon' => 'images/post/lKesPDuDhDtf8kmkT6tu847bHxe5DDIAQItP6G7z.png',
                        'number' => '25%',
                        'title' => 'Total Waste Managed',
                        'small_title' => 'Year 2025',
                        'image' => 'images/post/temp-card-item.jpg'
                    ]
                ]),
                'numbers_id' => json_encode([
                    [
                        'icon' => 'images/post/lKesPDuDhDtf8kmkT6tu847bHxe5DDIAQItP6G7z.png',
                        'number' => '25%',
                        'title' => 'Total Limbah yang di Kelola',
                        'small_title' => 'Tahun 2025',
                        'image' => 'images/post/temp-card-item.jpg'
                    ]
                ]),
            ],
            'policy_performance' => [
                'status_en' => 'active',
                'status_id' => 'active',
                'title_en' => 'Public Education',
                'title_id' => 'Edukasi Publik',
                'description_en' => 'Educating the public on waste management.',
                'description_id' => 'Mengedukasi publik tentang pengelolaan limbah.',
                'numbers_en' => json_encode([
                    [
                        'number' => '50+',
                        'title' => 'Workshops Held',
                        'small_title' => 'Since 2020',
                        'image' => 'images/post/temp-card-item.jpg'
                    ]
                ]),
                'numbers_id' => json_encode([
                    [
                        'number' => '50+',
                        'title' => 'Lokakarya Diadakan',
                        'small_title' => 'Sejak 2020',
                        'image' => 'images/post/temp-card-item.jpg'
                    ]
                ])
            ],
            'policy_items' => [
                'status' => 'active',
                'items_en' => json_encode([
                    [
                        'status' => 'active',
                        'title' => 'Waste Bank',
                        'description' => 'Community-based waste management.',
                        'cta_label' => 'Learn More',
                        'cta_url' => '#',
                        'image' => 'images/post/temp-card-item.jpg'
                    ],
                    [
                        'status' => 'active',
                        'title' => 'Plastic Asphalt',
                        'description' => 'Utilizing plastic waste for road infrastructure.',
                        'image' => 'images/post/temp-card-item.jpg',
                        'cta_label' => 'Learn More',
                        'cta_url' => '#'
                    ]
                ]),
                'items_id' => json_encode([
                    [
                        'status' => 'active',
                        'title' => 'Bank Sampah',
                        'description' => 'Pengelolaan sampah berbasis masyarakat.',
                        'cta_label' => 'Pelajari Lebih Lanjut',
                        'cta_url' => '#',
                        'image' => 'images/post/temp-card-item.jpg'
                    ],
                    [
                        'status' => 'active',
                        'title' => 'Aspal Plastik',
                        'description' => 'Memanfaatkan sampah plastik untuk infrastruktur jalan.',
                        'image' => 'images/post/temp-card-item.jpg',
                        'cta_label' => 'Pelajari Lebih Lanjut',
                        'cta_url' => '#'
                    ]
                ])
            ],
            'end_to_end_performance' => [
                'status_en' => 'active',
                'status_id' => 'active',
                'title_en' => 'End-to-End Waste Management',
                'title_id' => 'Pengelolaan Sampah Menyeluruh',
                'description_en' => '<p>Comprehensive waste management solutions.</p>',
                'description_id' => '<p>Solusi pengelolaan sampah yang komprehensif.</p>',
                'numbers_en' => json_encode([
                    ['icon' => 'images/post/lKesPDuDhDtf8kmkT6tu847bHxe5DDIAQItP6G7z.png', 'number' => '100%', 'title' => 'Recycled', 'small_title' => 'Success Rate', 'image' => 'images/post/temp-card-item.jpg']
                ]),
                'numbers_id' => json_encode([
                    ['icon' => 'images/post/temp-card-item.jpg', 'number' => '100%', 'title' => 'Didaur Ulang', 'small_title' => 'Tingkat Keberhasilan', 'image' => 'images/post/temp-card-item.jpg']
                ]),
            ],
            'end_to_end_items' => [
                'status' => 'active',
                'items_en' => json_encode([
                    [
                        'status' => 'active',
                        'title' => 'IPST Asari',
                        'description' => 'Integrated waste treatment facility.',
                        'image' => 'images/post/temp-card-item.jpg',
                        'cta_label' => 'View Facility',
                        'cta_url' => '#'
                    ]
                ]),
                'items_id' => json_encode([
                    [
                        'status' => 'active',
                        'title' => 'IPST Asari',
                        'description' => 'Fasilitas pengolahan sampah terpadu.',
                        'image' => 'images/post/temp-card-item.jpg',
                        'cta_label' => 'Lihat Fasilitas',
                        'cta_url' => '#'
                    ]
                ]),
            ],
            'technology_performance' => [
                'status_en' => 'active',
                'status_id' => 'active',
                'title_en' => 'Technology Innovation',
                'title_id' => 'Inovasi Teknologi',
                'description_en' => '<p>Leveraging technology for a circular future.</p>',
                'description_id' => '<p>Memanfaatkan teknologi untuk masa depan sirkular.</p>',
                'numbers_en' => json_encode([
                    ['icon' => 'images/post/lKesPDuDhDtf8kmkT6tu847bHxe5DDIAQItP6G7z.png', 'number' => '5', 'title' => 'Patents', 'small_title' => 'Granted', 'image' => 'images/post/temp-card-item.jpg']
                ]),
                'numbers_id' => json_encode([
                    ['icon' => 'images/post/temp-card-item.jpg', 'number' => '5', 'title' => 'Paten', 'small_title' => 'Diberikan', 'image' => 'images/post/temp-card-item.jpg']
                ]),
            ],
            'technology_items' => [
                'status' => 'active',
                'items_en' => json_encode([
                    [
                        'status' => 'active',
                        'title' => 'Pyrolysis',
                        'description' => 'Converting waste back into fuel/oil.',
                        'image' => 'images/post/temp-card-item.jpg',
                        'cta_label' => 'Technology Detail',
                        'cta_url' => '#'
                    ]
                ]),
                'items_id' => json_encode([
                    [
                        'status' => 'active',
                        'title' => 'Pirolisis',
                        'description' => 'Mengubah sampah kembali menjadi bahan bakar/minyak.',
                        'image' => 'images/post/temp-card-item.jpg',
                        'cta_label' => 'Detail Teknologi',
                        'cta_url' => '#'
                    ]
                ]),
            ]
        ];

        foreach ($metaData as $section => $fields) {
            foreach ($fields as $key => $value) {
                PostMeta::updateOrCreate(
                    [
                        'post_id' => $postId,
                        'section' => $section,
                        'key' => $key,
                    ],
                    [
                        'value' => $value,
                        'type' => Str::contains($key, ['numbers', 'items']) ? 'repeater' : 'text',
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $post = DB::table('posts')->where('slug', 'circular-economy-and-partnership')->first();
        if ($post) {
            DB::table('post_metas')->where('post_id', $post->id)->delete();
            DB::table('posts')->where('id', $post->id)->delete();
        }

        // Remove Sidebar Menu
        DB::table('sidebar_menus')->where('title', 'Circular Eco')->delete();
    }
};
