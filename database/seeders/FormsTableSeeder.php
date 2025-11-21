<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FormsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('forms')->delete();
        
        \DB::table('forms')->insert(array (
            0 => 
            array (
                'id' => '99caf33a-9e62-4556-9649-868069496e82',
                'name' => 'Contact Us',
                'auto_reply' => 1,
                'admin_email' => 'masitingss@gmail.com',
                'subject' => 'Auto Reply - Contact Us',
                'message' => '<p>Hi there,</p><p>Thank you for your inquiry. We will contact you as soon as we\'ve got this message.&nbsp;<br>If you think this message is urgent, please directly contact us at <strong>@antikode </strong>on Intagram.</p>',
                'deleted_at' => NULL,
                'created_at' => '2023-08-02 16:43:51',
                'updated_at' => '2023-08-06 21:59:38',
            ),
        ));
        
        
    }
}