<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('options')->delete();
        
        \DB::table('options')->insert(array (
            0 => 
            array (
                'id' => 1,
                'option_name' => 'mail.mailers.smtp.host',
                'option_value' => 'sandbox.smtp.mailtrap.io',
                'is_autoload' => 0,
                'created_at' => '2023-08-06 21:38:01',
                'updated_at' => '2023-08-06 21:38:01',
            ),
            1 => 
            array (
                'id' => 2,
                'option_name' => 'mail.mailers.smtp.port',
                'option_value' => '2525',
                'is_autoload' => 0,
                'created_at' => '2023-08-06 21:38:01',
                'updated_at' => '2023-08-06 21:38:01',
            ),
            2 => 
            array (
                'id' => 3,
                'option_name' => 'mail.mailers.smtp.encryption',
                'option_value' => 'tls',
                'is_autoload' => 0,
                'created_at' => '2023-08-06 21:38:01',
                'updated_at' => '2023-08-06 21:38:01',
            ),
            3 => 
            array (
                'id' => 4,
                'option_name' => 'mail.mailers.smtp.username',
                'option_value' => '75e2c61a07422c',
                'is_autoload' => 0,
                'created_at' => '2023-08-06 21:38:01',
                'updated_at' => '2023-08-06 21:38:01',
            ),
            4 => 
            array (
                'id' => 5,
                'option_name' => 'mail.mailers.smtp.password',
                'option_value' => '4803c373d27ce6',
                'is_autoload' => 0,
                'created_at' => '2023-08-06 21:38:01',
                'updated_at' => '2023-08-06 21:38:01',
            ),
            5 => 
            array (
                'id' => 6,
                'option_name' => 'mail.from.address',
                'option_value' => 'no-reply@antikode.com',
                'is_autoload' => 0,
                'created_at' => '2023-08-06 21:38:01',
                'updated_at' => '2023-08-06 21:38:01',
            ),
            6 => 
            array (
                'id' => 7,
                'option_name' => 'mail.from.name',
                'option_value' => 'No-Reply',
                'is_autoload' => 0,
                'created_at' => '2023-08-06 21:38:01',
                'updated_at' => '2023-08-06 21:38:01',
            ),
        ));
        
        
    }
}