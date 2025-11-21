<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FieldsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('fields')->delete();
        
        \DB::table('fields')->insert(array (
            0 => 
            array (
                'id' => '99cf0798-982f-4d19-8a5f-3d70080af41a',
                'sort' => 1,
                'label' => 'Full Name',
                'name' => 'full_name',
                'class' => NULL,
                'type' => 'input',
                'input' => 'text',
                'is_required' => 1,
                'placeholder' => 'Place your full name',
                'options' => NULL,
                'form_id' => '99caf33a-9e62-4556-9649-868069496e82',
                'deleted_at' => NULL,
                'created_at' => '2023-08-04 17:24:07',
                'updated_at' => '2023-08-04 17:24:07',
            ),
            1 => 
            array (
                'id' => '99cf07b2-f30d-4684-a03f-6248ae0846a7',
                'sort' => 2,
                'label' => 'Phone Number',
                'name' => 'phone_number',
                'class' => NULL,
                'type' => 'input',
                'input' => 'number',
                'is_required' => 0,
                'placeholder' => 'Place your phone number',
                'options' => NULL,
                'form_id' => '99caf33a-9e62-4556-9649-868069496e82',
                'deleted_at' => NULL,
                'created_at' => '2023-08-04 17:24:24',
                'updated_at' => '2023-08-04 17:24:24',
            ),
            2 => 
            array (
                'id' => '99cf07ea-cf32-477e-8eba-510cb5035791',
                'sort' => 3,
                'label' => 'Email address',
                'name' => 'email_address',
                'class' => NULL,
                'type' => 'input',
                'input' => 'email',
                'is_required' => 1,
                'placeholder' => 'Place your email address',
                'options' => NULL,
                'form_id' => '99caf33a-9e62-4556-9649-868069496e82',
                'deleted_at' => NULL,
                'created_at' => '2023-08-04 17:25:01',
                'updated_at' => '2023-08-04 17:25:01',
            ),
            3 => 
            array (
                'id' => '99cf07fd-cdae-4ccd-ad79-a011cafda8a7',
                'sort' => 5,
                'label' => 'Message',
                'name' => 'message',
                'class' => NULL,
                'type' => 'textarea',
                'input' => 'text',
                'is_required' => 1,
                'placeholder' => 'Place your message here.',
                'options' => '{"cols": "10", "rows": "10"}',
                'form_id' => '99caf33a-9e62-4556-9649-868069496e82',
                'deleted_at' => NULL,
                'created_at' => '2023-08-04 17:25:13',
                'updated_at' => '2023-08-04 17:25:49',
            ),
            4 => 
            array (
                'id' => '99cf082d-6288-4814-8665-00872c4a6bec',
                'sort' => 4,
                'label' => 'Country',
                'name' => 'country',
                'class' => NULL,
                'type' => 'select',
                'input' => 'text',
                'is_required' => 1,
                'placeholder' => 'Select your country',
                'options' => '[{"key": "id", "value": "Indonesia"}, {"key": "sg", "value": "Singapore"}, {"key": "my", "value": "Malaysia"}, {"key": "in", "value": "India"}]',
                'form_id' => '99caf33a-9e62-4556-9649-868069496e82',
                'deleted_at' => NULL,
                'created_at' => '2023-08-04 17:25:44',
                'updated_at' => '2023-08-04 17:25:49',
            ),
        ));
        
        
    }
}