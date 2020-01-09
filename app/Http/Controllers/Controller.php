<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function render($template, $params)
    {
        $baseParams = [
            'locales' => config('app')['locales'],
            'defaultLocale' => config('app')['locale']
        ];

        $formattedParams = array_merge($baseParams, $params);

        return Inertia::render($template, $formattedParams);
    }
}
