<?php

namespace App;

use App\RolePermissionConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubTheme extends Model
{
    protected $fillable = ['name', 'description',];

    public function getSubThemes()
    {
        $subThemes = $this->orderBy('created_at', 'desc')
            ->paginate()
            ->only('id', 'name')
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
            'status'    => 1,
            'id'        => $subTheme->id,
            'name'      => $subTheme->name,
        ];
    }

    public function addSubTheme($params)
    {
        try {
            DB::beginTransaction();

            $subTheme = $this;
            $subTheme->name = $params['name'];
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
