<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class AdminsController extends Controller
{
    public function index()
    {
        $admins = Admin::getAdmins();

        return $this->render('Admin/Admins/Index', [
            'filters' => Request::all('search', 'locality', 'first_name', 'last_name', 'email'),
            'admins' => $admins
        ]);
    }

    public function create()
    {
        $roles = Role::getRoles();

        return $this->render('Admin/Admins/Create', [
            'roles' => $roles['data'],
        ]);
    }

    public function store()
    {
        $rules = [
            'first_name'    => ['required', 'max:50'],
            'last_name'     => ['required', 'max:50'],
            'email'         => ['required', 'max:50', 'email', Rule::unique('admins')],
            'phone'         => ['required', 'max:10'],
            'role'          => ['required', 'integer', 'min:0'],
            'photo'         => ['nullable'],
            'password'      => ['nullable', 'min:6', 'max:50'],
            'password_auto_generation' => ['required'],
        ];

        $params = Request::validate($rules);

        $adminObject = new Admin;
        $adminObject->addAdmin($params);

        return Redirect::route('admin.admins')->with('success', 'User created.');
    }

    public function show($id)
    {
        $roles = Role::getRoles();
        $adminObject = new Admin;
        $admin = $adminObject->getAdmin($id);
        return $this->render('Admin/Admins/Edit', [
            'admin' => $admin,
            'roles' => $roles['data'],
        ]);
    }

    public function update($id)
    {
        $rules = [
            'first_name'    => ['required', 'max:50'],
            'last_name'     => ['required', 'max:50'],
            'email'         => ['required', 'max:50', 'email', Rule::unique('admins')->ignore($id)],
            'phone'         => ['required', 'max:10'],
            'role'          => ['required', 'integer', 'min:0'],
            'photo'         => ['nullable', 'image'],
        ];

        $params = Request::validate($rules);

        $admin = new Admin();
        $admin->updateAdmin($id, $params);

        return Redirect::route('admin.admins')->with('success', 'Admin updated.');
    }

    public function destroy($id)
    {
        $adminObject = new Admin();
        $admin = $adminObject->getAdmin($id);

        if (empty($admin)) {
            $result = [
                'status' => 'error',
                'message' => `The administrator is not found`
            ];
        } else {
            if ($admin['id'] ==  0) {
                $result = [
                    'status' => 'error',
                    'message' => `You can't delete yourself`
                ];
            } else {
                $admin->delete($id);
                $result = [
                    'status' => 'success',
                    'message' => `Admin deleted`
                ];
            }
        }


        return Redirect::route('users.edit', $admin)->with($result['status'], $result['message']);
    }

    public function restore($id)
    {
        $admin = new Admin();
        $admin->restore();

        return Redirect::route('users.edit', $admin)->with('success', 'User restored.');
    }
}
