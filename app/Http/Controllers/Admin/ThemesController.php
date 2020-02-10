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

        if (!empty($currentSubject)) {
            $result['current_subject'] = $currentSubject;
        }

        return Inertia::render('Admin/Themes/Index', $result);
    }

    public function store()
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
            'subject_id' => ['required', 'integer', 'min:0'],
        ]);

        $themeObject = new Theme;
        $themeObject->addTheme($params);

        return Redirect::route('admin.themes')->with('success', 'Theme created');
    }

    public function update($id)
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
            'subject_id' => ['required', 'integer', 'min:0'],
        ]);

        $themeObject = new Theme();
        $themeObject->updateTheme($id, $params);


        return Redirect::route('admin.themes')->with('success', 'Theme updated.');
    }

    public function destroy($id)
    {
        try {
            $themeObject = new Theme();
            $themeObject->deleteTheme($id);
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }

        return Redirect::route('admin.themes')->with('success', 'Theme deleted.');
    }
}
