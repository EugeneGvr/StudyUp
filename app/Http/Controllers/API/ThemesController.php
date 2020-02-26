<?php

namespace App\Http\Controllers\API;

use App\Models\Theme;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;


class ThemesController extends Controller
{
    public function index($id)
    {
        $params = [
            'subject_id' => $id,
        ];

        $themeObject = new Theme();
        $themes = $themeObject->getThemes($params, false);

        return !empty($themes) ? $themes : [];

    }
}
