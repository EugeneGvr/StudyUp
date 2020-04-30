<?php

namespace App\Models;

use App\Models\RolePermissionConnection;
use Illuminate\Database\Connection;
use App\Models\Model;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    protected $fillable = ['name', 'description',];

    public function getQuestions($params = [])
    {
        $questions = $this->where($params);
        $questions = $questions
            ->select(['questions.id AS id', 'questions.text AS text', 'sub_themes.name AS sub_theme', 'themes.name AS theme', 'subjects.name AS subject'])
            ->join('sub_themes', 'questions.sub_theme_id', '=', 'sub_themes.id')
            ->join('themes', 'sub_themes.theme_id', '=', 'themes.id')
            ->join('subjects', 'themes.subject_id', '=', 'subjects.id')
            ->orderBy('questions.created_at', 'desc')
            ->paginate()
            ->transform(function ($question) {
                return [
                    'id' => $question->id,
                    'text' => strlen($question->text) > 30 ?
                        sprintf('%s...', mb_substr($question->text, 0, 30)) :
                        $question->text,
                    'sub_theme' => $question->sub_theme,
                    'theme' => $question->theme,
                    'subject' => $question->subject,
                ];
            })
            ->toArray();

        return $questions;
    }

    public function getQuestionIdsForTest($id, $selector)
    {
        $selectorId = $selector.'_id';
        $questions = $this
            ->select(['questions.id AS id', 'themes.id AS theme_id', 'subjects.id AS subject_id'])
            ->join('sub_themes', 'questions.sub_theme_id', '=', 'sub_themes.id')
            ->join('themes', 'sub_themes.theme_id', '=', 'themes.id')
            ->join('subjects', 'themes.subject_id', '=', 'subjects.id')
            ->where([ $selectorId => $id])
            ->get()
            ->transform(function ($question) {
                return [
                    'id' => $question->id,
                    'theme_id' => $question->theme_id,
                    'subject_id' => $question->subject_id,
                ];
            })
            ->toArray();

        return $questions;
    }

    public function getQuestion($id, $withAnswerStatus = true)
    {
        try {
            $question = $this
                ->select([
                    'questions.id AS id',
                    'questions.text AS text',
                    'sub_themes.id AS subtheme_id',
                    'themes.id AS theme_id',
                    'subjects.id AS subject_id',
                    'questions.level',
                    'questions.answer_type',
                    'questions.photo_path',
                ])
                ->join('sub_themes', 'questions.sub_theme_id', '=', 'sub_themes.id')
                ->join('themes', 'sub_themes.theme_id', '=', 'themes.id')
                ->join('subjects', 'themes.subject_id', '=', 'subjects.id')
                ->find($id)
                ->toArray();

            if (!$question) {
                throw new \Exception("Question not found");
            }

            $answers = Answer::getAnswersByQuestionId($question['id'], $question['answer_type'], $withAnswerStatus);

            $photoPath = $this->getFilePublicUrl(
                $question['photo_path'],
                config('filesystems')['avatars']['questions']['path']
            );

            $result = [
                'id' => $question['id'],
                'text' => $question['text'],
                'subtheme_id' => $question['subtheme_id'],
                'theme_id' => $question['theme_id'],
                'subject_id' => $question['subject_id'],
                'level' => $question['level'],
                'answer_type' => $question['answer_type'],
                'answers' => $answers,
                'photo' => $photoPath,
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }

    public function addQuestion($params)
    {
        $avatarConfig = config('filesystems')['avatars'];
        try {
            DB::beginTransaction();

            $questionObject = $this;
            $questionObject->text = $params['text'];
            $questionObject->sub_theme_id = $params['subtheme_id'];
            $questionObject->level = $params['level'];
            $questionObject->answer_type = $params['answer_type'];
            $questionObject->save();

            $questionObject->photo_path = !empty($params['photo']) ?
                $this->uploadFile($params['photo'], $questionObject->id, $avatarConfig['questions']['path']) :
                null;
            $questionObject->save();

            foreach ($params['answers'] as $answer) {
                if ($questionObject->answer_type != 'correlation') {
                    if (!empty($answer['text'])) {
                        $answerObject = new Answer();
                        $answerId = $answerObject->addAnswer([
                            'text' => $answer['text']
                        ]);
                        $connectionObject = new AnswerQuestionConnections();
                        $connectionObject->addConnection([
                            'question_id' => $questionObject->id,
                            'answer_id' => $answerId,
                            'correct' => $answer['correct'] ?? 0
                        ]);
                    }
                } else {
                    if (!empty($answer['text1']) && !empty($answer['text2'])) {
                        $answer1Object = new Answer();
                        $answer1_id = $answer1Object->addAnswer([
                            'text' => $answer['text1']
                        ]);
                        $answer2Object = new Answer();
                        $answer2_id = $answer2Object->addAnswer([
                            'text' => $answer['text2']
                        ]);

                        $connection = new AnswerCorrelationQuestionConnections();
                        $connection->addConnection([
                            'question_id' => $questionObject->id,
                            'answer1_id' => $answer1_id,
                            'answer2_id' => $answer2_id,
                        ]);
                    }
                }
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateQuestion($id, $params)
    {
        $avatarConfig = config('filesystems')['avatars'];
        try {
            $questionObject = $this->find($id);
            if (!$questionObject) {
                throw new \Exception("Question not found");
            }

            DB::beginTransaction();
            $questionObject->text = $params['text'];
            $questionObject->sub_theme_id = $params['subtheme_id'];
            $questionObject->level = $params['level'];
            $questionObject->answer_type = $params['answer_type'];
            $questionObject->save();

            if (!empty($params['photo'])) {
                if (!empty($questionObject->photo_path)) {
                    $this->deleteFile($questionObject->photo_path);
                }
                $questionObject->photo_path = $this->uploadFile($params['photo'], $id, $avatarConfig['admins']['path']);
            }
            $questionObject->save();

            foreach ($params['answers'] as $answer) {
                if ($questionObject->answer_type == 'correlation') {
                    if (!empty($answer['text']) && !empty($answer['correct'])) {
                        $answerObject = new Answer();
                        $answer_id = $answerObject->addAnswer([
                            'text' => $answer['text']
                        ]);

                        $connectionObject = new AnswerQuestionConnections();
                        $connectionObject->addConnection([
                            'question_id' => $questionObject->id,
                            'answer_id' => $answer_id,
                            'correct' => $answer['correct']
                        ]);
                    }
                } else {
                    if (!empty($answer['text1']) && !empty($answer['text2'])) {
                        $answerObject = new Answer();
                        $answer1_id = $answerObject->addAnswer([
                            'text' => $answer['text1']
                        ]);
                        $answer2_id = $answerObject->addAnswer([
                            'text' => $answer['text2']
                        ]);

                        $connection = new AnswerCorrelationQuestionConnections();
                        $connection->addConnection([
                            'question_id' => $questionObject->id,
                            'answer1_id' => $answer1_id,
                            'answer2_id' => $answer2_id,
                        ]);
                    }
                }
            }
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
            $questionObject = $this->find($id);
            $answerObject = new Answer();
            if (!$questionObject) {
                throw new \Exception("Question not found");
            }
            DB::beginTransaction();

            $answers = Answer::getAnswersByQuestionId($questionObject->id, $questionObject->answer_type);
            $answerIds = array_column($answers,'Id');

            $answerObject->deleteAnswer($answerIds);
            $questionObject->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception("Something went wrong during deleting question: [".$e->getMessage()."]");
        }
    }
}
