<?php

use App\Models\Role;
use App\Models\RolePermissionConnection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

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
        Log::stack(['errorlog', 'slack'])->info('Role "Super administrator" is added');

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
