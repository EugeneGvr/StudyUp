<?php

namespace App\Http\Controllers\Admin;


use App\Models\Locality;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class LocalitiesController extends Controller
{
    public function index()
    {
        $params = Request::only('search', 'sort', 'parent_id');
        $localityObject = new Locality();
        $localitiesData = $localityObject->getLocalities($params, true);
        $currentLocality = $localityObject->getLocality($params['parent_id'] ?? 0);

        return $this->render('Admin/Localities/Index', [
            'types' => config('app')['localities']['types'],
            'filters' => Request::all('search', 'role', 'trashed'),
            'localities' => $localitiesData['localities'],
            'breadcrumb' => $localitiesData['breadcrumb'],
            'current' => $currentLocality,
        ]);
    }

    public function store()
    {
        $params = Request::validate([
            'name' => ['required', 'max:50'],
            'parent_id' => ['required', 'integer', 'min:0'],
        ]);

        $localitiesObject = new Locality();
        $localitiesObject->addLocality($params);

        return Redirect::route('admin.localities')->with('success', 'Locality created.');
    }

    public function update($id)
    {
        $params = Request::validate([
            'name' => ['required', 'max:50'],
        ]);

        $localityObject = new Locality();
        $localityObject->updateLocality($id, $params);

        return Redirect::route('admin.localities')->with('success', 'Locality updated.');
    }

    public function destroy($id)
    {
        try {
            $localityObject = new Locality();
            $localityObject->deleteLocality($id);
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }

        return Redirect::route('admin.localities')->with('success', 'Locality deleted.');
    }
}
