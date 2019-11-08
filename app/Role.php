<?php

namespace App;

use App\RolePermissionConnection;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'description',];

    public static function getRoles()
    {
        $roles = self::orderBy('created_at', 'desc')->paginate()->only('id', 'name')->toArray();

        foreach ($roles['data'] as $key => &$role) {
            $permissions = [];

            foreach (config('permissions') as $module => $actions) {
                $roleActions = RolePermissionConnection::where('role_id', $role['id'])->where('module', $module)->count();
                $permission = [
                    'module'    => ucfirst($module),
                    'lvl'       => $roleActions . '/' . count($actions)
                ];
                array_push($permissions, $permission);
            }
            $role['permissions'] = $permissions;
        }

        return $roles;
    }

    public static function getRole($id)
    {
        $role = self::find($id);

        if(!$role) {
            return 'ERROR f*** U';
        }

        $permissions = [];

        foreach (RolePermissionConnection::where('role_id', $id)->get() as $permission) {
            $permissions[] = $permission->module . '.' . $permission->action;
        }

        return [
            'id'            => $role->id,
            'name'          => $role->name,
            'description'   => $role->description,
            'permissions'   => $permissions
        ];
    }

    public function addRole($params)
    {

        try {
            $role = $this;

            $role->name = $params['name'];
            $role->description = $params['description'];
            $role->save();
        } catch (\Exception $e) {
            return 'Something went wrong during role creating';
        }

        $permissions= [];

        try {
            foreach ($params['permissions'] as $permission) {
                list($module, $action) = explode('.', $permission);

                $rolePermissionConnection = new RolePermissionConnection();
                $rolePermissionConnection->role_id = $role->id;
                $rolePermissionConnection->module = $module;
                $rolePermissionConnection->action = $action;
                $rolePermissionConnection->save();
                $permissions[$permission] = $rolePermissionConnection;
            }
        } catch (\Exception $e) {
            $role->delete();
            foreach ($permissions as $permission) {
                $permission->delete();
            }
            return 'Something went wrong during creating connection with role and permission';
        }

        return [
          'status' => 1
        ];
    }

    public function updateRole($id, $params) {
        try {
            $role = $this->find($id);

            if (!$role) {
                throw new \Exception("Role not found");
            }

            $role->name = $params['name'];
            $role->description = $params['description'];
            $role->save();

            $permissionKeys = array_flip($params['permissions']);
            $rolePermissions = RolePermissionConnection::where('role_id', $id)->get();

            foreach ($rolePermissions as $rolePermission) {
                $rolePermissionKey = $rolePermission['module'].'.'.$rolePermission['action'];

                if (in_array($rolePermissionKey, $params['permissions'])) {
                    unset($permissionKeys[$rolePermissionKey]);
                } else {
                    RolePermissionConnection::where('role_id', $id)
                        ->where('module',  $rolePermission['module'])
                        ->where('action',  $rolePermission['action'])
                        ->delete();
                }
            }

            foreach (array_flip($permissionKeys) as $newPermission) {
                list($module, $action) = explode('.', $newPermission);
                $rolePermissionConnection = new RolePermissionConnection();
                $rolePermissionConnection->role_id = $id;
                $rolePermissionConnection->module = $module;
                $rolePermissionConnection->action = $action;
                $rolePermissionConnection->save();
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteRole($id)
    {

            $role = $this->find($id);

            if (!$role) {
                throw new \Exception("Role not found");
            }
            $rolePermissions = RolePermissionConnection::where('role_id', $id);

            try {
                $role->delete();
                $rolePermissions->delete();
            } catch (\Exception $e) {
                throw new \Exception("Something went wrong during deleting role");
            }


    }
}
