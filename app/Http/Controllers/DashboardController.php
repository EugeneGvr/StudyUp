<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $subjectObject = new Subject();
        $localities = $subjectObject->getSubjects([], false);

        return $this->render('Dashboard/Index', [
            'subjects' => $localities,
        ]);
    }
}
