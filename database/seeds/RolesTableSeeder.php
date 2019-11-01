<?php

use App\Role;
use App\RolePermissionConnection;
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
        $role = Role::create([
            'name'          => 'Super administrator',
            'description'   => 'This is role with all actions permitted',
        ]);

        $tables =  ['admins', 'users', 'roles'];
        $actions = ['read', 'create', 'edit', 'delete'];

        foreach ($tables as $table) {

            foreach ($actions as $action) {
                RolePermissionConnection::create([
                    'role_id'          => $role->id,
                    'permission_key'   => $table.'.'.$action,
                ]);
            }
        }
    }
}
