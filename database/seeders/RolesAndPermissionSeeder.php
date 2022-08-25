<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'delete posts']);
        Permission::create(['name' => 'publish posts']);
        Permission::create(['name' => 'unpublish posts']);

        $user_role = Role::create(['name' => 'user'])
            ->givePermissionTo(Permission::all());

        $moderator_role = Role::create(['name' => 'moderator'])
            ->givePermissionTo(['publish posts', 'unpublish posts']);

        $super_admin_role = Role::create(['name' => 'Super-Admin']);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example User',
            'email' => 'user@example.com',
        ]);
        $user->assignRole($user_role);

        $moderator = \App\Models\User::factory()->create([
            'name' => 'Example Moderator',
            'email' => 'moderator@example.com',
        ]);
        $moderator->assignRole($moderator_role);

        $super_admin = \App\Models\User::factory()->create([
            'name' => 'Example Super Admin',
            'email' => 'super_admin@example.com',
        ]);
        $super_admin->assignRole($super_admin_role);
    }
}
