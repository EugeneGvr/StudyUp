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
        $subjects = $subjectObject->getSubjects(false);

        $result = [
            'subjects' => $subjects,
            'answer_types' => config('app')['answer_types']
        ];

        return $this->render('Admin/Questions/Create', $result);
    }

    public function store()
    {
        $params = Request::validate([
            'text' => ['required', 'max:512'],
            'subtheme_id'   => ['required', 'integer', 'min:0'],
            'level'         => ['required', 'integer', 'min:1', 'max:10'],
            'answer_type'   => ['required'],
            'answers'       => ['required'],
            'photo'         => ['nullable', 'image'],
        ]);

        $questionObject = new Question();
        $questionObject->addQuestion($params);

        return Redirect::route('admin.questions')->with('success', 'Question created');
    }

    public function edit($id)
    {
        $questionObject = new Question();
        $data = $questionObject->getQuestion($id);

        if(!empty($data['status']) || $data['status'] == 1) {
            return $this->render('Admin/Questions/Edit', [
                'question' => $data['question']
            ]);
        }

        return Redirect::route('admin.questions')->with('error', $data['message']);
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
