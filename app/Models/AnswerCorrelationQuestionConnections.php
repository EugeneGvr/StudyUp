<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AnswerCorrelationQuestionConnections extends Model
{
    public function getConnections($params = [])
    {
        $connections = $this->where($params);
        $connections = $connections
            ->orderBy('created_at', 'desc')
            ->get()
            ->transform(function ($connection) {
                return [
                    'question_id' => $connection->question_id,
                    'answer1_id' => $connection->answer1_id,
                    'answer2_id' => $connection->answer2_id,
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
            $connection->answer1_id = $params['answer1_id'];
            $connection->answer2_id = $params['answer2_id'];
            $connection->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' =>'Something went wrong during creating connection',
            ];
        }

        return [
            'status' => 1
        ];
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

        return [
            'status' => 1
        ];
    }
}
