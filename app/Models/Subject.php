<?php

namespace App\Models;

use App\RolePermissionConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subject extends Model
{
    protected $fillable = ['name', 'description',];

    public function getSubjects($paginate = true)
    {
        $subjects = $this->orderBy('created_at', 'desc');
        $subjects = $paginate ? $subjects->paginate() : $subjects->get();
        $subjects = $subjects->transform(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->name,
            ];
        })->toArray();

        return $subjects;
    }

    public function getSubject($id)
    {
        $subject = self::find($id);

        if(!$subject) {
            return 'Subject not found';
        }

        return [
            'status'    => 1,
            'id'        => $subject->id,
            'name'      => $subject->name,
        ];
    }

    public function addSubject($params)
    {
        try {
            DB::beginTransaction();

            $subject = $this;
            $subject->name = $params['name'];
            $subject->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' =>'Something went wrong during creating subject',
            ];
        }

        return [
            'status' => 1
        ];
    }

    public function updateSubject($id, $params)
    {
        try {
            $subject = $this->find($id);
            if (!$subject) {
                throw new \Exception("Subject not found");
            }

            DB::beginTransaction();
            $subject->name = $params['name'];
            $subject->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' => 'Something went wrong during updating subject. Message: ['.$e->getMessage().']',
            ];
        }

        return [
            'status' => 1
        ];
    }

    public function deleteSubject($id)
    {
        try {
            $subject = $this->find($id);

            if (!$subject) {
                return [
                    'status' => 0,
                    'message' => 'Subject not found',
                ];
            }
            DB::beginTransaction();
            $subject->delete();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' => 'Something went wrong during deleting subject: ['.$e->getMessage().']',
            ];
        }

        return [
            'status' => 1
        ];
    }
}
