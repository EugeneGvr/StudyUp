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

    public static function getAnswersByQuestionId($questionId, $answerType, $withStatus = true)
    {
        if ($answerType != 'correlation') {
            $select = [
                'answers.id AS id',
                'answers.text AS text',
            ];
            if ($withStatus) {
                $select[] = 'answer_question_connections.correct AS correct';
            }
            $answers = AnswerQuestionConnections::select($select)
                ->join('answers', 'answer_question_connections.answer_id', '=', 'answers.id')
                ->where(['question_id' => $questionId])
                ->get()
                ->toArray();
        } else {
            $answers = AnswerCorrelationQuestionConnections::select([
                'answer1.id AS id1',
                'answer2.id AS id2',
                'answer1.text as text1',
                'answer2.text as text2',
            ])
                ->join('answers as answer1', 'answer_correlation_question_connections.answer1_id', '=', 'answer1.id')
                ->join('answers as answer2', 'answer_correlation_question_connections.answer2_id', '=', 'answer2.id')
                ->where(['question_id' => $questionId])
                ->get()
                ->toArray();
        }

        return $answers;
    }

    public function addAnswer($params) : int
    {
        try {
            DB::beginTransaction();

            $answer = $this;
            $answer->text = $params['text'];
            $answer->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception("Something went wrong during creating answer");

        }

        return $answer->id;
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

    public function deleteAnswer($id)
    {
        try {
            $answer = $this->find($id);
            if (!$answer) {
                throw new \Exception("Answer not found");
            }

            $answer->delete();

        } catch (\Exception $e) {
            throw new \Exception("Something went wrong during deleting answer: [".$e->getMessage()."]");
        }
    }
}
