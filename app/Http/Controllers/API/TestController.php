<?php

namespace App\Http\Controllers\API;


use App\Models\Admin;
use App\Models\SubTheme;
use App\Http\Controllers\Controller;
use App\Traits\TestGenerator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    use TestGenerator;
    public function getQuestion()
    {
        $rules = [
            'field' => ['required'],
            'id'    => ['required' ,'integer'],
            'type'  => ['required'],
        ];

        $params = Request::validate($rules);
        $params['userId'] = Auth::id();

        $question = $this->generateQuestion($params);

        return $question;
    }

    public function answerQuestion()
    {
        return 'hihih';
    }
}
