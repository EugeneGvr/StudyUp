<?php

namespace App\Http\Controllers\Admin;

use App\Subject;
use App\Theme;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class ThemesController extends Controller
{
    public function index()
    {
        $params = Request::only('search', 'sort', 'subject_id');

        $subjectObject = new Subject();
        $currentSubject = $subjectObject->getSubject($params['subject_id']);
        $subjects = $subjectObject->getSubjects();

        $themeObject = new Theme();
        $themes = $themeObject->getThemes($params);

        $result = [
            'filters' => Request::all('search', 'role', 'trashed'),
            'themes' => $themes,
            'subjects' => $subjects,
        ];

        if (!empty($subject)) {
            $result['current_subject'] = $currentSubject;
        }

        return Inertia::render('Admin/Themes/Index', $result);
    }

    public function store()
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
        ]);

        $theme = new Theme;
//        $theme->addTheme($params);

        return Redirect::route('admin.themes')->with('success', 'Theme created');
    }

    public function edit($theme)
    {
        return Inertia::render('Admin/Theme/Edit', [
            'user' => [
                'id' => $theme->id,
                'name' => $theme->name,
                'subject_id' => $theme->subject_id,
            ],
        ]);
    }

    public function update($theme)
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

        return Redirect::route('admin.themes', $theme)->with('success', 'User updated.');
    }

    public function destroy($theme)
    {
//        $user->delete();

        return Redirect::route('admin.themes.edit', $theme)->with('success', 'Theme deleted.');
    }

    public function restore($theme)
    {
//        $user->restore();

        return Redirect::route('admin.themes.edit', $theme)->with('success', 'Theme restored.');
    }
}
