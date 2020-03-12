<?php

namespace App\Traits;

use App\Models\Question;

trait TestGenerator
{
    public function generateQuestion($params = [])
    {
        $questionObject = new Question();
        if(!empty($params['id']) && !empty($params['field'])) {
            $questions = $questionObject->getQuestionIdsForTest($params['id'], $params['field']);
        } else {
            throw new \Exception("Not all needed data available");
        }

        $questionId = $questions[rand(0, count($questions)-1)]['id'];
        $question = $questionObject->getQuestion($questionId);

        return $question;
    }
}
