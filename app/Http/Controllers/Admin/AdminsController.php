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

        return $this->render('Admins/Index', [
            'filters' => Request::all('search', 'locality', 'first_name', 'last_name', 'email'),
            'admins' => $admins
        ]);
    }

    public function create()
    {
        $roles = Role::getRoles();

        return $this->render('Admins/Create', [
            'roles' => $roles,
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

        $admin = new Admin;
        $admin->addAdmin($params);

        return Redirect::route('admin.admins')->with('success', 'User created.');
    }

    public function show($id)
    {
        $roles = Role::getRoles();
        $admin = Admin::getAdmin($id);
        return $this->render('Admins/Edit', [
            'admin' => [
                'id' => $admin->id,
                'first_name' => $admin->first_name,
                'last_name' => $admin->last_name,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'role' => $admin->role,
                'photo' => $admin->photoUrl(['w' => 60, 'h' => 60, 'fit' => 'crop']),
            ],
            'roles' => $roles,
        ]);
    }

    public function update($id)
    {
        $params = Request::validate([
            'first_name'    => ['required', 'max:50'],
            'last_name'     => ['required', 'max:50'],
            'email'         => ['required', 'max:50', 'email', Rule::unique('users')],
            'phone'         => ['required', 'max:10'],
            'role'          => ['required', 'positive_integer'],
            'password'      => ['nullable'],
            'photo'         => ['nullable', 'image'],
        ]);

        $admin = new Admin();
        $admin->updateAdmin($id, $params);

        if (Request::file('photo')) {
            $admin->update(['photo_path' => Request::file('photo')->store('admins')]);
        }

        if (Request::get('password')) {
            $admin->update(['password' => Request::get('password')]);
        }

        return Redirect::route('admin.admins')->with('success', 'Admin updated.');
    }

    public function destroy($id)
    {
        $admin = new Admin();
        $admin->delete($id);

        return Redirect::route('users.edit', $admin)->with('success', 'User deleted.');
    }

    public function restore($id)
    {
        $admin = new Admin();
        $admin->restore();

        return Redirect::route('users.edit', $admin)->with('success', 'User restored.');
    }
}
