<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $blogPostType = DB::table('post_types')->where('slug', 'blog')->first();

        if ($blogPostType) {
            DB::table('post_types')
                ->where('slug', 'blog')
                ->update([
                    'is_category' => true,
                    'updated_at' => now(),
                ]);
        } else {
            $user = DB::table('users')->first();
            $userId = $user ? $user->id : 1;
            $maxSort = DB::table('post_types')->max('sort') ?? 0;

            DB::table('post_types')->insert([
                'id' => (string) Str::uuid(),
                'sort' => $maxSort + 1,
                'name' => 'Blog',
                'slug' => 'blog',
                'type' => 'post',
                'is_public' => true,
                'show_in_menu' => true,
                'is_category' => true,
                'is_tags' => true,
                'is_content' => true,
                'featured_image' => true,
                'featured' => true,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $existingBlogCat = DB::table('categories')->where('type', 'blog')->first();
        if (!$existingBlogCat) {
            DB::table('categories')->insert([
                'type' => 'blog',
                'name' => 'General',
                'name_en' => 'General',
                'slug' => 'general',
                'slug_en' => 'general',
                'description' => 'General blog category',
                'description_en' => 'General blog category',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('post_types')
            ->where('slug', 'blog')
            ->update([
                'is_category' => false,
                'updated_at' => now(),
            ]);
    }
};
