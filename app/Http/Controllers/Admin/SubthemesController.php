<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\SubTheme;
use App\Models\Theme;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class SubthemesController extends Controller
{
    public function index()
    {
        $params = Request::only('search', 'sort', 'subject_id');

        $subjectObject = new Subject();
        $subjects = $subjectObject->getSubjects();

        $themeObject = new Theme();
        $themes = $themeObject->getThemes($params);

        $subThemeObject = new SubTheme();
        $subThemes = $subThemeObject->getSubThemes($params);

        $result = [
            'filters' => Request::all('search', 'role', 'trashed'),
            'sub_themes' => $subThemes,
            'themes' => $themes,
            'subjects' => $subjects,
        ];

        if (!empty($params['theme_id'])) {
            $result['current_theme'] = $themeObject->getTheme($params['theme_id']);
        }

        return Inertia::render('Admin/Subthemes/Index', $result);
    }

    public function store()
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
            'theme_id' => ['required', 'integer', 'min:0'],
        ]);

        $subTheme = new subTheme();
        $subTheme->addSubTheme($params);

        return Redirect::route('admin.subthemes')->with('success', 'Subtheme created');
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
