<?php

namespace Database\Seeders;

use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        $admin = Role::create(['name' => 'admin', 'guard_name' => backpack_guard_name()]);

        $permissions = Permission::where('guard_name', backpack_guard_name())->get();
        foreach ($permissions as $permission) {
            $admin->givePermissionTo($permission);
        }
    }
}
