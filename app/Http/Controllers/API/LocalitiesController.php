<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Locality;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class LocalitiesController extends Controller
{
    public function index()
    {
        $params = Request::only('parent_id');
        $localityObject = new Locality();
        $localitiesData = $localityObject->getLocalities($params, false, false);

        return !empty($localitiesData['localities']) ? $localitiesData['localities'] : [];
    }
}
