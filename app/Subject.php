<?php

namespace App;

use App\RolePermissionConnection;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'description',];

    public static function getSubjects()
    {
        $subjects = self::orderBy('created_at', 'desc')->paginate()->only('id', 'name')->toArray();

        return $subjects;
    }

    public static function getSubject($id)
    {
        $subject = self::find($id);

        if(!$subject) {
            return 'Subject not found';
        }


        return [
            'id'            => $subject->id,
            'name'          => $subject->name,
        ];
    }

//    public function addRole($params)
//    {
//
//        try {
//            $role = $this;
//
//            $role->name = $params['name'];
//            $role->description = $params['description'];
//            $role->save();
//        } catch (\Exception $e) {
//            return 'Something went wrong during role creating';
//        }
//
//        $permissions= [];
//
//        try {
//            foreach ($params['permissions'] as $permission) {
//                if($permission != 'all.all' && count(explode('.', $permission)) > 1) {
//                    list($module, $action) = explode('.', $permission);
//
//                    $rolePermissionConnection = new RolePermissionConnection();
//                    $rolePermissionConnection->role_id = $role->id;
//                    $rolePermissionConnection->module = $module;
//                    $rolePermissionConnection->action = $action;
//                    $rolePermissionConnection->save();
//                    $permissions[$permission] = $rolePermissionConnection;
//                }
//            }
//        } catch (\Exception $e) {
//            $role->delete();
//            foreach ($permissions as $permission) {
//                $permission->delete();
//            }
//            return 'Something went wrong during creating connection with role and permission';
//        }
//
//        return [
//          'status' => 1
//        ];
//    }
//
//    public function updateRole($id, $params) {
//        try {
//            $role = $this->find($id);
//
//            if (!$role) {
//                throw new \Exception("Role not found");
//            }
//
//            $role->name = $params['name'];
//            $role->description = $params['description'];
//            $role->save();
//
//            $permissionKeys = array_flip($params['permissions']);
//            $rolePermissions = RolePermissionConnection::where('role_id', $id)->get();
//
//            foreach ($rolePermissions as $rolePermission) {
//                $rolePermissionKey = $rolePermission['module'].'.'.$rolePermission['action'];
//
//                if (in_array($rolePermissionKey, $params['permissions'])) {
//                    unset($permissionKeys[$rolePermissionKey]);
//                } else {
//                    RolePermissionConnection::where('role_id', $id)
//                        ->where('module',  $rolePermission['module'])
//                        ->where('action',  $rolePermission['action'])
//                        ->delete();
//                }
//            }
//
//            foreach (array_flip($permissionKeys) as $newPermission) {
//                list($module, $action) = explode('.', $newPermission);
//                $rolePermissionConnection = new RolePermissionConnection();
//                $rolePermissionConnection->role_id = $id;
//                $rolePermissionConnection->module = $module;
//                $rolePermissionConnection->action = $action;
//                $rolePermissionConnection->save();
//            }
//
//        } catch (\Exception $e) {
//            return $e->getMessage();
//        }
//    }
//
//    public function deleteRole($id)
//    {
//
//            $role = $this->find($id);
//
//            if (!$role) {
//                throw new \Exception("Role not found");
//            }
//            $rolePermissions = RolePermissionConnection::where('role_id', $id);
//
//            try {
//                $role->delete();
//                $rolePermissions->delete();
//            } catch (\Exception $e) {
//                throw new \Exception("Something went wrong during deleting role");
//            }
//
//
//    }
}
