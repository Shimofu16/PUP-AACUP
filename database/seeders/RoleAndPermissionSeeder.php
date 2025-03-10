<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

         // create permissions
         Permission::create(['name' => 'view_user']);
         Permission::create(['name' => 'add_user']);
         Permission::create(['name' => 'edit_user']);
         Permission::create(['name' => 'delete_user']);

         Permission::create(['name' => 'view_article']);
         Permission::create(['name' => 'add_article']);
         Permission::create(['name' => 'edit_article']);
         Permission::create(['name' => 'delete_article']);

         Permission::create(['name' => 'view_program']);
         Permission::create(['name' => 'add_program']);
         Permission::create(['name' => 'edit_program']);
         Permission::create(['name' => 'delete_program']);


        $role = Role::create(['name' => 'faculty']);
        $role->givePermissionTo([
            'view_article',
            'add_article',
            'edit_article',
            'delete_article',
        ]);

        $role = Role::create(['name' => 'committee_reviewer']);
        $role->givePermissionTo([
            'view_article',
            'edit_article',
            'delete_article',
        ]);
         $role = Role::create(['name' => 'admin']);
         $role->givePermissionTo(Permission::all());

    }
}
