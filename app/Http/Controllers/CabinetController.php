<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class CabinetController extends Controller
{
    public function index()
    {
//        return Inertia::render('Admin/Subjects/Index', [
//            'filters' => Request::all('search', 'role', 'trashed'),
//            'subjects' => $subjects,
//        ]);
    }
}
