<?php

namespace App\Http\Controllers\Admin;

use App\Models\Question;
use App\Models\Subject;
use App\Models\Theme;
use App\Models\SubTheme;
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

        $questionObject = new Question();
        $questions = $questionObject->getQuestions($params);

        $result = [
            'filters' => Request::all('search', 'role', 'trashed'),
            'questions' => $questions,
        ];

        return Inertia::render('Admin/Questions/Index', $result);
    }

    public function create()
    {
        $subjectObject = new Subject();
        $subjects = $subjectObject->getSubjects();

        $result = [
            'subjects' => $subjects,
            'answer_types' => config('app')['answer_types']
        ];

        return $this->render('Admin/Question/Create', $result);
    }

    public function store()
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
            'theme_id' => ['required', 'integer', 'min:0'],
            'sub_theme_id' => ['required', 'integer', 'min:0'],
        ]);

        $questionObject = new Question();
        $questionObject->addQuestion($params);


        return Redirect::route('admin.questions')->with('success', 'Question created');
    }

    public function show($id)
    {
        $questionObject = new Question();
        $question = $questionObject->getQuestion($id);

        $result = [
            'question' => $question,
        ];

        return $this->render('Admin/Question/Edit', $result);
    }

    public function update($id)
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
            'theme_id' => ['required', 'integer', 'min:0'],
            'sub_theme_id' => ['required', 'integer', 'min:0'],
        ]);

        $questionObject = new Question();
        $questionObject->updateQuestion($id, $params);

        return Redirect::route('admin.questions')->with('success', 'Question updated.');
    }

    public function destroy($id)
    {
        try {
            $questionObject = new Question();
            $questionObject->deleteQuestion($id);
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }

        return Redirect::route('admin.questions')->with('success', 'Question deleted.');
    }
}
