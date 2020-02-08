<?php

namespace App\Http\Controllers\Admin;


use App\Models\Role;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class RolesController extends Controller
{
    public function index()
    {
        $params = Request::only('search', 'sort');

        $roles = Role::getRoles($params);

        return $this->render('Admin/Roles/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $allPermissions = config('permissions');

        return $this->render('Admin/Roles/Create', [
            'allPermissions' => $allPermissions
        ]);
    }

    public function store()
    {
        $params = Request::validate([
            'name' => ['required', 'max:50', Rule::unique('roles')],
            'description' => ['required', 'max:255'],
            'permissions' => ['required'],
        ]);

        $role = new Role;
        $role->addRole($params);

        return Redirect::route('admin.roles')->with('success', 'Role created.');
    }

    public function show($id)
    {
        $allPermissions = config('permissions');
        $role = Role::getRole($id);

        return $this->render('Admin/Roles/Edit', [
            'allPermissions' => $allPermissions,
            'role' => $role
        ]);
    }

    public function update($id)
    {
        $params = Request::validate([
            'name' => ['required', 'max:50'],
            'description' => ['required', 'max:255'],
            'permissions' => ['required'],
        ]);

        $role = new Role();
        $role->updateRole($id, $params);

        return Redirect::route('admin.roles')->with('success', 'Role updated.');
    }

    public function destroy($id)
    {
        try {
            $role = new Role();
            $role->deleteRole($id);
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }

        return Redirect::route('admin.roles')->with('success', 'Role deleted.');
    }
}
