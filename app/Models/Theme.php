<?php

namespace App\Models;

use App\RolePermissionConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Theme extends Model
{
    protected $fillable = ['name', 'description',];

    public function getThemes($params = [], $paginate = true)
    {
        $themes = $this->where($params);
        $themes = $themes
            ->select([
                'themes.id AS id',
                'themes.name AS name',
                'themes.subject_id AS subject_id',
                'subjects.name AS subject_name'
            ])
            ->join('subjects', 'themes.subject_id', '=', 'subjects.id')
            ->orderBy('themes.created_at', 'desc');
        $themes = $paginate ? $themes->paginate() : $themes->get();
        $themes = $themes->transform(function ($theme) {
            return [
                'id' => $theme->id,
                'name' => $theme->name,
                'subject_id' => $theme->subject_id,
                'subject_name' => $theme->subject_name,
            ];
        })->toArray();

        return $themes;
    }

    public function getTheme($id)
    {
        $theme = self::find($id);

        if(!$theme) {
            return 'Theme not found';
        }

        return [
            'status'     => 1,
            'id'         => $theme->id,
            'name'       => $theme->name,
            'subject_id' => $theme->subject_id,
        ];
    }

    public function addTheme($params)
    {
        try {
            DB::beginTransaction();

            $theme = $this;
            $theme->name = $params['name'];
            $theme->subject_id = $params['subject_id'];
            $theme->save();

            $subTheme = new SubTheme();
            $subTheme->name = $params['name'];
            $subTheme->theme_id = $theme->id;
            $subTheme->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' =>'Something went wrong during creating theme',
            ];
        }

        return [
            'status' => 1
        ];
    }

    public function updateTheme($id, $params)
    {
        try {
            $theme = $this->find($id);
            if (!$theme) {
                throw new \Exception("Theme not found");
            }

            DB::beginTransaction();
            $theme->name = $params['name'];
            $theme->subject_id = $params['subject_id'];
            $theme->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' => 'Something went wrong during updating theme. Message: ['.$e->getMessage().']',
            ];
        }

        return [
            'status' => 1
        ];
    }

    public function deleteTheme($id)
    {
        try {
            $theme = $this->find($id);

            if (!$theme) {
                return [
                    'status' => 0,
                    'message' => 'Theme not found',
                ];
            }
            DB::beginTransaction();
            $theme->delete();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' => 'Something went wrong during deleting theme: ['.$e->getMessage().']',
            ];
        }

        return [
            'status' => 1
        ];
    }
}
