<?php

namespace App\Traits;

use App\Http\Middleware\Authenticate;
use App\Models\Question;
use App\Models\Statistics;
use App\Models\DetailedStatistics;
use Illuminate\Support\Facades\Auth;

trait TestGenerator
{
    public function generateQuestion($params = [])
    {
        $questionObject = new Question();
        $statisticsObject = new Statistics();
        if(!empty($params['id']) && !empty($params['field'])) {
            $questions = $questionObject->getQuestionIdsForTest($params['id'], $params['field']);
        } else {
            throw new \Exception("Not all needed data available");
        }
        if ($statisticsObject->hasStats(Auth::user()->id)) {
            $worstQuestionsIds = $questionObject->getWorstResult(Auth::user()->id, $params['id'], $params['field']);
            $filteredQuestions = [];
            foreach ($worstQuestionsIds as $id) {
                $filteredQuestions[] = $questions[$id];
            }
            $questions = $filteredQuestions;
        }

        $questionId = $questions[rand(0, count($questions)-1)]['id'];
        $question = $questionObject->getQuestion($questionId, false);

        return $question;
    }

    public function checkAnswer($params = [])
    {
        $questionObject = new Question();
        $statisticsObject = new Statistics();

        $question = $questionObject->find($params['question_id']);
        if (!$question) {
            throw new \Exception("Question not found");
        }
        $correctAnswerIds = $questionObject->getCorrectAnswer($question['id']);
        usort($params['answer']['ids'], function($a, $b) {
            return $a[0] - $b[0];
        });
        $answerCoef = 0;

        if (in_array($question['answer_type'], ['correlation', 'multi'])) {
            foreach ($correctAnswerIds as $key => $correctAnswerIdsPair) {
                $answerCoef += $correctAnswerIdsPair == $params['answer']['ids'][$key] ?
                1/count($correctAnswerIds) :
                0;
            }
        } else {
            $answerCoef = $correctAnswerIds == $params['answer']['ids'] ? 1 : 0;
        }

        $scoreCoef = $params['type'] == 'firstWrong' ? (1+($params['question']['order']-1)/10) : 1;
        $score = $answerCoef * $scoreCoef * $question['level'];

        DetailedStatistics::updateStatistics($question, floor($answerCoef));
        $statisticsObject->updateStatistics();

        return [
            'score' => $score ?? 0,
            'correct' => floor($answerCoef)
        ];
    }
}
