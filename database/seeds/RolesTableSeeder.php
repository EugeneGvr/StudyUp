<?php

use App\Models\Role;
use App\Models\RolePermissionConnection;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = config('permissions');

        $role = Role::create([
            'name'          => 'Super administrator',
            'description'   => 'This is role with all actions permitted',
        ]);

        foreach ($permissions as $module => $actions) {

            foreach ($actions as $action) {
                RolePermissionConnection::create([
                    'role_id'   => $role->id,
                    'module'    => $module,
                    'action'    => $action
                ]);
            }
        }
    }
}
