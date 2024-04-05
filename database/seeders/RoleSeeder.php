<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // create Roles
         $admin = Role::factory()->create([
            'name' => 'Admin'
        ]);

        $editor =Role::factory()->create([
            'name' => 'Editor'
        ]);

        $viewer = Role::factory()->create([
            'name' => 'Viewer'
        ]);

        // assgning permission to all admin
        $permissions = Permission::all();

        // pluck function will return array of id for permissions
        $admin->permissions()->attach($permissions->pluck('id'));

        $editor->permissions()->attach($permissions->pluck('id'));

        // $viewer->permissions()->attach($permissions->pluck('id'));

        // editor will all permissions expect 4
        $editor->permissions()->detach(4);

        // viewer will all permissions expect 1,3,5,7
        $viewer->permissions()->detach([1,3,5,7]);



        // foreach($permissions as $permission){
        //     \DB::table('role_permission')->insert([
        //         'permission_id' => $permission->id,
        //         'role_id' => $admin->id
        //     ]);
        // }
    }
}
