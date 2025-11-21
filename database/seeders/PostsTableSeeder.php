<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('posts')->delete();
        
        \DB::table('posts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type' => 'news',
                'title' => 'Hello World',
                'slug' => 'hello-world',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sit amet leo ipsum. Curabitur fermentum finibus lectus id egestas. Ut sit amet erat efficitur purus commodo accumsan.',
                'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sit amet leo ipsum. Curabitur fermentum finibus lectus id egestas. Ut sit amet erat efficitur purus commodo accumsan. Nunc suscipit dictum luctus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel maximus lorem. Nam pellentesque lacinia ex scelerisque aliquet. Donec blandit commodo hendrerit. In non lorem libero. Vivamus at nunc nisl. Maecenas egestas purus vel risus aliquet, ac euismod lacus dignissim. Pellentesque eget diam sed magna tempus volutpat non non odio. Nullam ullamcorper quam nulla, id sagittis nibh pretium et.</p><p>Vivamus orci ante, gravida ut egestas sit amet, rhoncus aliquam ex. Nam congue in nisl a gravida. Praesent non dolor sed risus pretium luctus id at quam. Aenean ipsum sapien, malesuada et mattis id, mollis in sem. Vestibulum mi justo, fermentum ac felis vitae, facilisis vestibulum felis. Etiam laoreet libero maximus ipsum auctor, sit amet sollicitudin velit ultricies. In tellus diam, luctus ut semper sed, hendrerit sit amet elit. Sed facilisis nulla ante, sit amet tempor urna condimentum sit amet. Nullam eleifend pretium ligula. Vestibulum non purus eget neque dapibus facilisis ut nec ipsum.</p><p>Curabitur semper dolor non mi lobortis lobortis. Curabitur fermentum aliquet elit. Praesent odio enim, tempus et ornare ut, laoreet a nulla. Mauris dignissim turpis at eleifend pellentesque. Sed elementum porttitor nibh, nec facilisis lectus placerat a. Etiam scelerisque eleifend ligula vitae porta. Duis non maximus est. Donec tincidunt, ante non lacinia rhoncus, elit diam imperdiet risus, in bibendum dolor mi pharetra nulla. Vestibulum nisi nunc, imperdiet ut quam at, tincidunt tincidunt nunc. Phasellus interdum enim in mattis hendrerit. Fusce sodales dignissim ex, eget sodales elit consequat a. Etiam vel nisl augue.</p><p>Nulla facilisi. Vivamus et viverra dui. Quisque finibus eros sit amet nisi pulvinar, at consequat lectus venenatis. Donec gravida ante vitae neque consequat, eget sollicitudin dui ultrices. Phasellus tincidunt odio posuere feugiat sollicitudin. Etiam scelerisque eleifend ipsum, in ornare felis vehicula vitae. Morbi id accumsan augue. Aliquam mattis eros ipsum, eget cursus arcu convallis non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nullam ut viverra est, sed luctus ante.</p><p>Integer lectus lorem, posuere non sagittis at, consectetur vitae nisi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nam pellentesque arcu eget turpis ultrices, id aliquam ipsum convallis. Fusce vestibulum massa ut lacus dignissim lacinia. Maecenas ornare elit hendrerit urna eleifend, vel auctor urna volutpat. Donec felis quam, tincidunt ac turpis vel, sollicitudin congue lectus. Sed pulvinar accumsan suscipit. Quisque et sollicitudin diam. Fusce facilisis eros tellus, vitae tempor lorem efficitur vel. Mauris dapibus pharetra nisi, varius feugiat ipsum porta rhoncus. Suspendisse sit amet mattis quam, id lobortis felis. Donec sed neque finibus, tempor tellus eget, hendrerit tellus. Aliquam lobortis ligula nec dignissim sagittis.</p>',
                'status' => 'publish',
                'featured' => 'yes',
                'published_at' => NULL,
                'post_comment' => 1,
                'post_password' => NULL,
                'post_type_id' => NULL,
                'user_id' => 1,
                'parent' => NULL,
                'meta_title' => 'Hello World',
                'meta_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sit amet leo ipsum. Curabitur fermentum finibus lectus id egestas. Ut sit amet erat efficitur purus com',
                'meta_keyword' => 'meta, keyword, are, here',
                'share_count' => 0,
                'like_count' => 0,
                'view_count' => 0,
                'deleted_at' => NULL,
                'created_at' => '2023-08-07 09:33:01',
                'updated_at' => '2023-08-07 09:33:01',
            ),
        ));
        
        
    }
}