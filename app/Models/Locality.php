<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Locality extends Model
{
    public function getLocalities($params = [], $childrenCheck = false)
    {
        if (empty($params))
        {
            $params['parent_id'] = 0;
        }
//        $localities = self::sort(!empty($params['sort']) ? $params['sort'] : 'date');
//        $localities = self::search($localities, !empty($params['search']) ? $params['search'] : null);
        $localities = $this->where($params);
        $localities = $localities->paginate()
            ->transform(function ($locality) {
                return [
                    'id' => $locality->id,
                    'name' => $locality->name,
                    'type' => $locality->type,
                ];
            })
            ->toArray();

        if ($childrenCheck) {
            $localityIds =  array_column($localities['data'], 'id');
            $localitiesWithChildren = self::whereIn('parent_id', $localityIds)
                ->get()
                ->groupBy('parent_id')
                ->toArray();
            $localityWithChildrenIds = array_keys($localitiesWithChildren);

            foreach ($localities['data'] as &$locality) {
                $locality['has_children'] = in_array($locality['id'], $localityWithChildrenIds);
            }
        }
        $breadcrumb = self::getBreadcrumb($params['parent_id']);

        return [
            'localities' => $localities,
            'breadcrumb' => $breadcrumb,
        ];
    }

    public function getLocality($id)
    {
        $locality = $this->find($id);

        if(!$locality) {
            return 'No locality with such id';
        }

        return [
            'id'    => $locality->id,
            'name'  => $locality->name,
            'type'  => $locality->type,
        ];
    }

    public function addLocality($params)
    {
        try {
            DB::beginTransaction();

            $locality = $this;
            $locality->name = $params['name'];
            $locality->parent_id = $params['parent_id'];
            $locality->type = $params['type'];
            //delete after new migration
            $locality->code = '000000';
            $locality->center = false;
            $locality->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' =>'Something went wrong during creating locality',
            ];
        }

        return [
            'status' => 1
        ];
    }

    public function updateLocality($id, $params)
    {
        try {
            $locality = $this->find($id);
            if (!$locality) {
                throw new \Exception("Locality not found");
            }

            DB::beginTransaction();
            $locality->name = $params['name'];
            $locality->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' => 'Something went wrong during updating an locality. Message: ['.$e->getMessage().']',
            ];
        }
    }

    public function deleteLocality($id)
    {
        $locality = $this->find($id);

        if (!$locality) {
            throw new \Exception("Locality not found");
        }

        try {
            $locality->delete();
        } catch (\Exception $e) {
            throw new \Exception("Something went wrong during deleting locality");
        }
    }

    public static function getBreadcrumb($parentId)
    {
        $breadcrumb = [];
        $root = [
            'id'        => '0',
            'name'      => 'Украина',
            'current'   => $parentId == 0 ? true : false,
        ];

        $current = true;
        while ($parentId != 0) {
            $currentLocale = self::find($parentId);
            $breadcrumbElement = [
                'id'        => $currentLocale->id,
                'name'      => $currentLocale->name,
                'current'   => $current,
            ];
            array_unshift($breadcrumb, $breadcrumbElement);

            $current = false;
            $parentId = $currentLocale->parent_id;
        }
        array_unshift($breadcrumb, $root);

        return $breadcrumb;
    }
}
