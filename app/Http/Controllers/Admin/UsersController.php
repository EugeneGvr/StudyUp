<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::getUsers();
        return $this->render('Admin/Users/Index', [
            'filters' => Request::all('search', 'locality', 'created_at', 'first_name', 'last_name', 'email'),
            'users' => $users
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store()
    {
        Request::validate([
            'first_name'    => ['required', 'max:50'],
            'last_name'     => ['required', 'max:50'],
            'email'         => ['required', 'max:50', 'email', Rule::unique('users')],
            'password'      => ['nullable'],
            'phone'         => ['required', 'max:10'],
            'locality'      => ['required', 'max:70'],
            'photo'         => ['nullable', 'image'],
        ]);

        Auth::user()->account->users()->create([
            'first_name'    => Request::get('first_name'),
            'last_name'     => Request::get('last_name'),
            'email'         => Request::get('email'),
            'password'      => Request::get('password'),
            'owner'         => Request::get('owner'),
            'phone'         => Request::get('phone'),
            'locality'      => Request::get('locality'),
            'photo_path'    => Request::file('photo') ? Request::file('photo')->store('users') : null,
        ]);

        return Redirect::route('users')->with('success', 'User created.');
    }

    public function edit($user)
    {
        return $this->render('Admin/Users/Edit', [
            'user' => [
                'id'            => $user->id,
                'first_name'    => ['required', 'max:50'],
                'last_name'     => ['required', 'max:50'],
                'email'         => ['required', 'max:50', 'email', Rule::unique('users')],
                'password'      => ['nullable'],
                'phone'         => ['required', 'boolean'],
                'locality'      => ['required', 'max:70'],
                'photo'         => ['nullable', 'image'],
            ],
        ]);
    }

    public function update($user)
    {
        Request::validate([
            'first_name'    => ['required', 'max:50'],
            'last_name'     => ['required', 'max:50'],
            'email'         => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password'      => ['nullable'],
            'owner'         => ['required', 'boolean'],
            'photo'         => ['nullable', 'image'],
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

    public function destroy($user)
    {
        $user->delete();

        return Redirect::route('users.edit', $user)->with('success', 'User deleted.');
    }

    public function restore($user)
    {
        $user->restore();

        return Redirect::route('users.edit', $user)->with('success', 'User restored.');
    }
}
