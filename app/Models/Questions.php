<?php

namespace App;

use App\RolePermissionConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Questions extends Model
{
    protected $fillable = ['name', 'description',];

    public function getQuestions($params = [])
    {
        $questions = $this->where($params);
        $questions = $questions
            ->select(['questions.id AS id', 'questions.question_text AS text', 'sub_themes.name AS subtheme', 'themes.name AS theme', 'subjects.name AS subject'])
            ->join('sub_themes', 'questions.sub_theme_id', '=', 'sub_themes.id')
            ->join('themes', 'sub_themes.theme_id', '=', 'themes.id')
            ->join('subjects', 'themes.subject_id', '=', 'subjects.id')
            ->orderBy('questions.created_at', 'desc')
            ->paginate()
            ->transform(function ($question) {

                return [
                    'id' => $question->id,
                    'text' => $question->text,
                    'subtheme' => $question->subtheme,
                    'theme' => $question->theme,
                    'subject' => $question->subject,
                ];
            })
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
            'text'      => $question->question_text,
            'subtheme_id'   => $question->sub_theme_id,
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
