<?php

namespace App\Http\Controllers\Admin;

use App\Questions;
use App\Subject;
use App\Theme;
use App\SubTheme;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class QuestionsController extends Controller
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

        $questionObject = new Questions();
        $questions = $questionObject->getQuestions($params);

        $result = [
            'filters' => Request::all('search', 'role', 'trashed'),
            'questions' => $questions,
            'sub_themes' => $subThemes,
            'themes' => $themes,
            'subjects' => $subjects,
        ];

        if (!empty($params['theme_id'])) {
            $result['current_theme'] = $themeObject->getTheme($params['theme_id']);;
        }
        if (!empty($params['subTheme_id'])) {
            $result['current_sub_theme'] = $subThemeObject->getSubTheme($params['subTheme_id']);
        }

        return Inertia::render('Admin/Questions/Index', $result);
    }

    public function store()
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
            'theme_id' => ['required', 'integer', 'min:0'],
            'sub_theme_id' => ['required', 'integer', 'min:0'],
        ]);

        $questionObject = new Questions();
        $questionObject->addQuestion($params);


        return Redirect::route('admin.questions')->with('success', 'Question created');
    }

    public function update($id)
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
            'theme_id' => ['required', 'integer', 'min:0'],
            'sub_theme_id' => ['required', 'integer', 'min:0'],
        ]);

        $questionObject = new Questions();
        $questionObject->updateQuestion($id, $params);

        return Redirect::route('admin.questions')->with('success', 'Question updated.');
    }

    public function destroy($id)
    {
        try {
            $questionObject = new Questions();
            $questionObject->deleteQuestion($id);
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }

        return Redirect::route('admin.questions')->with('success', 'Question deleted.');
    }
}
