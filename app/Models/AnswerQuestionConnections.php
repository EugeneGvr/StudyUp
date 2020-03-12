<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AnswerQuestionConnections extends Model
{
    public static function getConnections($params = [])
    {
        $connections = self::where($params);
        $connections = $connections
            ->get()
            ->transform(function ($connection) {
                return [
                    'question_id' => $connection->question_id,
                    'answer_id' => $connection->answer_id,
                    'correct' => $connection->correct,
                ];
            })
            ->toArray();

        return $connections;
    }

    public function addConnection($params)
    {
        try {
            DB::beginTransaction();

            $connection = $this;
            $connection->question_id = $params['question_id'];
            $connection->answer_id = $params['answer_id'];
            $connection->correct = $params['correct'];
            $connection->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw new \Exception($e->getMessage());
        }
    }

    public function deleteConnection($params)
    {
        try {
            $connection = $this->where($params);

            if (!$connection) {
                return [
                    'status' => 0,
                    'message' => 'Connection not found',
                ];
            }
            $connection->delete();

        } catch (\Exception $e) {
            return [
                'status' => 0,
                'message' => 'Something went wrong during deleting connection: ['.$e->getMessage().']',
            ];
        }
    }
}
