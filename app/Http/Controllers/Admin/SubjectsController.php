<?php

namespace App\Http\Controllers\Admin;

use App\Models\Locality;
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
        $subjectObject = new Subject();
        $subjects = $subjectObject->getSubjects();

        return Inertia::render('Admin/Subjects/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'subjects' => $subjects,
            ]);
    }

    public function store()
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
        ]);

        $subjectObject = new Subject();
        $subjectObject->addSubject($params);

        return Redirect::route('admin.subjects')->with('success', 'Subject created');
    }

    public function update($id)
    {
        $params = Request::validate([
            'name' => ['required', 'max:128'],
        ]);

        $subjectObject = new Subject();
        $subjectObject->updateSubject($id, $params);

        return Redirect::route('admin.subjects')->with('success', 'Subject updated.');
    }

    public function destroy($id)
    {
        try {
            $subjectObject = new Subject();
            $subjectObject->deleteSubject($id);
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }

        return Redirect::route('admin.subjects')->with('success', 'Subject deleted.');
    }
}
