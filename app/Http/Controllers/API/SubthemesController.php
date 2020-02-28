<?php

namespace App\Http\Controllers\API;


use App\Models\SubTheme;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class SubthemesController extends Controller
{
    public function index($id)
    {
        $params = [
            'theme_id' => $id,
        ];

        $subThemeObject = new SubTheme();
        $subThemes = $subThemeObject->getSubThemes($params, false);

        return !empty($subThemes) ? $subThemes : [];
    }
}
