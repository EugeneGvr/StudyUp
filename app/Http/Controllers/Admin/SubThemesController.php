<?php

namespace App\Http\Controllers\Admin;

use App\Subject;
use App\SubTheme;
use App\Theme;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class SubThemesController extends Controller
{
    public function index()
    {
        $params = Request::only('search', 'sort', 'subject_id');

        $subjectObject = new Subject();
        $subjects = $subjectObject->getSubjects();

        $themeObject = new Theme();
        $currentTheme = $themeObject->getTheme($params['theme_id']);
        $themes = $themeObject->getThemes($params);

        $subThemeObject = new SubTheme();
        $subThemes = $subThemeObject->getSubThemes($params);

        $result = [
            'filters' => Request::all('search', 'role', 'trashed'),
            'sub_themes' => $subThemes,
            'themes' => $themes,
            'subjects' => $subjects,
        ];

        if (!empty($currentTheme)) {
            $result['current_theme'] = $currentTheme;
        }

        return Inertia::render('Admin/SubThemes/Index', $result);
    }

    public function store()
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
            'theme_id' => ['required', 'integer', 'min:0'],
        ]);

        $theme = new Theme;
        $theme->addTheme($params);

        return Redirect::route('admin.themes')->with('success', 'Theme created');
    }

    public function update($id)
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
            'theme_id' => ['required', 'integer', 'min:0'],
        ]);

        $subThemeObject = new SubTheme();
        $subThemeObject->updateSubTheme($id, $params);

        return Redirect::route('admin.subthemes')->with('success', 'Subtheme updated.');
    }

    public function destroy($id)
    {
        try {
            $subThemeObject = new SubTheme();
            $subThemeObject->deleteSubTheme($id);
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }

        return Redirect::route('admin.subthemes')->with('success', 'Subtheme deleted.');
    }
}
