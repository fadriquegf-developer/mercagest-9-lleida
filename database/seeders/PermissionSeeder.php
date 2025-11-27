<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        $entities = [
            'absences',
            'sectors',
            'auth_prods',
            'persons',
            'towns',
            'markets',
            'market_groups',
            'calendar',
            'stalls',
            'extensions',
            'bonuses',
            'incidences',
            'contact_emails',
            'rates',
            //'concessions',
            'historicals',
            'invoices',
            'communications',
            'users',
            'roles',
            'permissions',
            'settings',
            'expedientes',
            'classes',
            'checklists',
            'reasons'
        ];

        $permissions = [
            'show', 'list', 'create', 'update', 'delete'
        ];

        foreach ($entities as $entity) {
            foreach ($permissions as $permission) {
                Permission::create(['name' => "$entity.$permission", 'guard_name' => backpack_guard_name()]);
            }
        }

        // special modules
        $specialPermissions =[
            'logs.manage',
            'maps',
            'day_report',
            'report'
        ];

        foreach ($specialPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => backpack_guard_name()]);
        }
    }
}
