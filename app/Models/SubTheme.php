<?php

namespace App\Models;

use App\RolePermissionConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubTheme extends Model
{
    protected $fillable = ['name', 'description',];

    public function getSubThemes($params = [], $paginate = true)
    {
        $subThemes = $this->where($params);
        $subThemes = $subThemes
            ->select(['sub_themes.id AS id', 'sub_themes.name AS name', 'themes.name AS theme', 'subjects.name AS subject'])
            ->join('themes', 'sub_themes.theme_id', '=', 'themes.id')
            ->join('subjects', 'themes.subject_id', '=', 'subjects.id')
            ->orderBy('sub_themes.created_at', 'desc');
        $subThemes = $paginate ? $subThemes->paginate() : $subThemes->find();
        $subThemes = $subThemes->transform(function ($subTheme) {
            return [
                'id' => $subTheme->id,
                'name' => $subTheme->name,
                'theme' => $subTheme->theme,
                'subject' => $subTheme->subject,
            ];
        })
        ->toArray();

        return $subThemes;
    }

    public function getSubTheme($id)
    {
        $subTheme = $this->find($id);

        if(!$subTheme) {
            return [
                'status'    => 0,
                'message'   => 'Subtheme not found',
            ];
        }

        return [
            'status'     => 1,
            'id'         => $subTheme->id,
            'name'       => $subTheme->name,
            'theme_id' => $subTheme->theme_id,
        ];
    }

    public function addSubTheme($params)
    {
        try {
            DB::beginTransaction();

            $subTheme = $this;
            $subTheme->name = $params['name'];
            $subTheme->theme_id = $params['theme_id'];
            $subTheme->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' =>'Something went wrong during creating subtheme',
            ];
        }

        return [
            'status' => 1
        ];
    }

    public function updateSubTheme($id, $params)
    {
        try {
            $subTheme = $this->find($id);
            if (!$subTheme) {
                throw new \Exception("Subtheme not found");
            }

            DB::beginTransaction();
            $subTheme->name = $params['name'];
            $subTheme->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' => 'Something went wrong during updating subtheme. Message: ['.$e->getMessage().']',
            ];
        }

        return [
            'status' => 1
        ];
    }

    public function deleteSubTheme($id)
    {
        try {
            $subTheme = $this->find($id);

            if (!$subTheme) {
                return [
                    'status' => 0,
                    'message' => 'Subtheme not found',
                ];
            }
            $subTheme->delete();

        } catch (\Exception $e) {
            return [
                'status' => 0,
                'message' => 'Something went wrong during deleting subtheme: ['.$e->getMessage().']',
            ];
        }

        return [
            'status' => 1
        ];
    }
}
