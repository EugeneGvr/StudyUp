<?php

namespace App;

use App\RolePermissionConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Questions extends Model
{
    protected $fillable = ['name', 'description',];

    public function getQuestions()
    {
        $questions = $this->orderBy('created_at', 'desc')
            ->paginate()
            ->only('id', 'name')
            ->toArray();

        return $questions;
    }

    public function getQuestion($id)
    {
        $question = $this->find($id);

        if(!$question) {
            return [
                'status'    => 0,
                'message'   => 'Question not found',
            ];
        }

        return [
            'status'    => 1,
            'id'        => $question->id,
            'name'      => $question->name,
        ];
    }

    public function addQuestion($params)
    {
        try {
            DB::beginTransaction();

            $question = $this;
            $question->name = $params['name'];
            $question->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' =>'Something went wrong during creating question',
            ];
        }

        return [
            'status' => 1
        ];
    }

    public function updateQuestion($id, $params)
    {
        try {
            $question = $this->find($id);
            if (!$question) {
                throw new \Exception("Question not found");
            }

            DB::beginTransaction();
            $question->name = $params['name'];
            $question->save();
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

    public function deleteQuestion($id)
    {
        try {
            $question = $this->find($id);

            if (!$question) {
                return [
                    'status' => 0,
                    'message' => 'Question not found',
                ];
            }
            $question->delete();

        } catch (\Exception $e) {
            return [
                'status' => 0,
                'message' => 'Something went wrong during deleting question: ['.$e->getMessage().']',
            ];
        }

        return [
            'status' => 1
        ];
    }
}
