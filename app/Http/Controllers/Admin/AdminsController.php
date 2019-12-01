<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Role;
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

        return Inertia::render('Admins/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'admins' => $admins
//            'filters' => Request::all('search', 'role', 'trashed'),
//            'administrators' => Admin::
//                ->orderByName()
//                ->filter(Request::only('search', 'role', 'trashed'))
//                ->get()
//                ->transform(function ($administrator) {
//                    return [
//                        'id' => $administrator->id,
//                        'name' => $administrator->name,
//                        'email' => $administrator->email,
//                        'owner' => $administrator->owner,
//                        'photo' => $administrator->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
//                        'deleted_at' => $administrator->deleted_at,
//                    ];
//                }),

        ]);
    }

    public function create()
    {
        $roles = Role::getRoles();

        return Inertia::render('Admins/Create', [
            'roles' => $roles,
        ]);
    }

    public function store()
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        Auth::user()->account->users()->create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'owner' => Request::get('owner'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('users') : null,
        ]);

        return Redirect::route('users')->with('success', 'User created.');
    }

    public function edit($admin)
    {
        $v=5;
        return Inertia::render('Admins/Edit', [
            'admin' => [
                'name'=> '',
          'surname' => '',
          'email' => '',
          'phone' => '',
          'role' => '',
          'photo' => '',
//                'id' => $user->id,
//                'first_name' => $user->first_name,
//                'last_name' => $user->last_name,
//                'email' => $user->email,
//                'owner' => $user->owner,
//                'photo' => $user->photoUrl(['w' => 60, 'h' => 60, 'fit' => 'crop']),
//                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }

    public function update(User $user)
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        $user->update(Request::only('first_name', 'last_name', 'email', 'owner'));

        if (Request::file('photo')) {
            $user->update(['photo_path' => Request::file('photo')->store('users')]);
        }

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::route('users.edit', $user)->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return Redirect::route('users.edit', $user)->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::route('users.edit', $user)->with('success', 'User restored.');
    }
}
