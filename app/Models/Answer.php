<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Answer extends Model
{
    public static function getAnswers($params = [])
    {
        $answers = self::where($params);
        $answers = $answers
            ->orderBy('created_at', 'desc')
            ->get()
            ->transform(function ($answer) {
                return [
                    'id' => $answer->id,
                    'text' => $answer->text,
                ];
            })
            ->toArray();

        return $answers;
    }

    public static function getAnswersByQuestionId($questionId, $answerType)
    {
        if ($answerType != 'correlation') {
            $answers = AnswerQuestionConnections::getConnections(['question_id' => $questionId]);
            error_log(print_r($answers,1));die;
        } else {
            $answers = AnswerCorrelationQuestionConnections::getConnections(['question_id' => $questionId]);
        }


        $answers = self::where($params);
        $answers = $answers
            ->orderBy('created_at', 'desc')
            ->get()
            ->transform(function ($answer) {
                return [
                    'id' => $answer->id,
                    'text' => $answer->text,
                ];
            })
            ->toArray();

        return $answers;
    }

    public function addAnswer($params)
    {
        try {
            DB::beginTransaction();

            $answer = $this;
            $answer->text = $params['text'];
            $answer->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' =>'Something went wrong during creating answer',
            ];
        }

        return [
            'answer_id' => $answer->id,
            'status' => 1
        ];
    }

    public function updateAnswers($id, $params)
    {
        try {
            $answer = $this->find($id);
            if (!$answer) {
                throw new \Exception("Answer not found");
            }

            DB::beginTransaction();
            $answer->name = $params['name'];
            $answer->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' => 'Something went wrong during updating question. Message: ['.$e->getMessage().']',
            ];
        }

        return [
            'status' => 1
        ];
    }

    public function deleteAnswer($params)
    {
        try {
            $answer = $this->where($params);

            if (!$answer) {
                return [
                    'status' => 0,
                    'message' => 'Answer not found',
                ];
            }
            $answer->delete();

        } catch (\Exception $e) {
            return [
                'status' => 0,
                'message' => 'Something went wrong during deleting answer: ['.$e->getMessage().']',
            ];
        }

        return [
            'status' => 1
        ];
    }
}
