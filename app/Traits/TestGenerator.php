<?php

namespace App\Traits;

use App\Models\Question;

trait TestGenerator
{
    public function getQuestion($params = [])
    {
        $questionObject = new Question();
        if(!empty($params['id']) && !empty($params['field'])) {
            $questions = $questionObject->getQuestionIdsForTest($params['id'], $params['field']);
        } else {
            throw new \Exception("Not all needed data available");
        }
$v = rand(0, count($questions)-1);
        $questionId = $questions[$v]['id'];
        $question = $questionObject->getQuestion($questionId);

        return $question;
    }
}
