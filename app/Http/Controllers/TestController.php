<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Theme;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function __invoke($id)
    {
        $themeObject = new Theme();
        $themes = $themeObject->getThemes(['subject_id' => $id], false);

        return $this->render('Test/Index', [
            'themes' => $themes,
            'subject' => $id,
        ]);
    }
}
