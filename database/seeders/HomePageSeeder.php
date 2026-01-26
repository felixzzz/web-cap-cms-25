<?php

namespace Database\Seeders;

use App\Domains\Post\Models\Post;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $homePage = Post::where('site_url', '/')->first();

        if (!$homePage) {
            Post::create([
                'title' => 'Home Page',
                'slug' => 'home',
                'site_url' => '/',
                'type' => 'page',
                'status' => 'publish',
                'user_id' => 1, // Assuming admin user ID is 1
            ]);

            $this->command->info('Home Page post created successfully.');
        } else {
            $this->command->info('Home Page post already exists.');
        }
    }
}
