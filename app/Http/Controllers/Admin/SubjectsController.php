<?php

namespace App\Http\Controllers\Admin;

use App\Subject;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class SubjectsController extends Controller
{
    public function index()
    {
        $themes = Subject::getSubjects();

        return Inertia::render('Subjects/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'admins' => $themes,
            ]);
    }

    public function create()
    {
        $subjects = Subject::getSubjects();

        return Inertia::render('Subjects/Create', [
            'subjects' => $subjects
        ]);
    }

    public function store()
    {
        $params = Request::validate([
            'title' => ['required', 'max:128'],
            'subThemeId' => ['required', 'max:50'],
            'subjectId' => ['required'],
        ]);

        $theme = new Subject();
//        $theme->addTheme($params);

        return Redirect::route('admin.subjects')->with('success', 'Subject created');
    }

    public function edit($subject)
    {
        return Inertia::render('Subjects/Edit', [
            'user' => [
                'id' => $subject->id,
                'name' => $subject->name,
            ],
        ]);
    }

    public function update($subject)
    {
//        Request::validate([
//            'first_name' => ['required', 'max:50'],
//            'last_name' => ['required', 'max:50'],
//            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
//            'password' => ['nullable'],
//            'owner' => ['required', 'boolean'],
//            'photo' => ['nullable', 'image'],
//        ]);
//
//        $user->update(Request::only('first_name', 'last_name', 'email', 'owner'));
//
//        if (Request::file('photo')) {
//            $user->update(['photo_path' => Request::file('photo')->store('users')]);
//        }
//
//        if (Request::get('password')) {
//            $user->update(['password' => Request::get('password')]);
//        }

        return Redirect::route('admin.subjects', $subject)->with('success', 'Subject updated.');
    }

    public function destroy($subject)
    {
//        $user->delete();

        return Redirect::route('admin.subjects.edit', $subject)->with('success', 'Subject deleted.');
    }

    public function restore($subject)
    {
//        $user->restore();

        return Redirect::route('admin.subjects.edit', $subkect)->with('success', 'Subject restored.');
    }
}
