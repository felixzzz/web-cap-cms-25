<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Create Roles
        $role = Role::create([
            'id' => 1,
            'type' => User::TYPE_ADMIN,
            'name' => 'Administrator',
        ]);

        $staffRole = Role::create([
            'id' => 2,
            'type' => User::TYPE_ADMIN,
            'name' => 'Staff',
        ]);

        // Grouped permissions
        $permission = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.user',
            'description' => 'Manage all user permissions',
        ]);

        $form = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.forms',
            'description' => 'Manage All Form',
        ]);

        $submission = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.form-submission',
            'description' => 'Manage all form submission',
        ]);

        $fields = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.form-fields',
            'description' => 'Manage All Form Fields',
        ]);

        $showSubmission = new Permission([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.form-submission.show',
            'description' => 'View Submission',
        ]);

        $showFields = new Permission([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.form-fields.show',
            'description' => 'View Form Fields',
        ]);

        $showForm = new Permission([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.forms.show',
            'description' => 'View Form',
        ]);

        $submission->children()->saveMany([
            $showSubmission,
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.form-submission.delete',
                'description' => 'Delete Submission',
            ]),
        ]);
//
        $fields->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.form-fields.create',
                'description' => 'Create Form Fields',
            ]),
            $showFields,
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.form-fields.edit',
                'description' => 'Edit Form Fields',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.form-fields.delete',
                'description' => 'Delete Form Fields',
            ]),
        ]);

        $form->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.forms.create',
                'description' => 'Create Form',
            ]),
            $showForm,
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.forms.edit',
                'description' => 'Edit Form',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.forms.delete',
                'description' => 'Delete Form',
            ]),
        ]);


        $permission->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.list',
                'description' => 'View Users',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.deactivate',
                'description' => 'Deactivate Users',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.reactivate',
                'description' => 'Reactivate Users',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.clear-session',
                'description' => 'Clear User Sessions',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.impersonate',
                'description' => 'Impersonate Users',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.change-password',
                'description' => 'Change User Passwords',
            ]),
        ]);

        // Assign Permissions to other Roles
        //
        $role->syncPermissions($permission);
        $role->syncPermissions($form);
        $role->syncPermissions($submission);
        $role->syncPermissions($fields);
        $staffRole->syncPermissions([
            $showFields, $showSubmission, $showForm
        ]);
        $this->enableForeignKeys();
    }
}
