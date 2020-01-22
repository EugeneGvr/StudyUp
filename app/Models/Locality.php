<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    public static function getLocalities($params = [])
    {
        if (empty($params))
        {
            $params['parent_id'] = 0;
        }
//        $localities = self::sort(!empty($params['sort']) ? $params['sort'] : 'date');
//        $localities = self::search($localities, !empty($params['search']) ? $params['search'] : null);
        $localities = self::where($params);
        $localities = $localities->paginate()
            ->transform(function ($locality) {
                return [
                    'id' => $locality->id,
                    'code' => $locality->code,
                    'name' => $locality->name,
                ];
            })
            ->toArray();

        $breadcrumb = self::getBreadcrumb($params['parent_id']);

        return [
            'localities' => $localities,
            'breadcrumb' => $breadcrumb,
        ];
    }

    public static function getBreadcrumb($parentId)
    {
        $breadcrumb = [];
        $root = [
            'title'         => 'Украина',
            'parent_id'     => '0',
            'current'       => $parentId == 0 ? true : false,
        ];

        $current = true;
        while ($parentId != 0) {
            $currentLocale = self::find($parentId);
            $breadcrumbElement = [
                'title'         => $currentLocale->name,
                'parent_id'     => $currentLocale->id,
                'current'       => $current,
            ];
            array_unshift($breadcrumb, $breadcrumbElement);

            $current = false;
            $parentId = $currentLocale->parent_id;
        }
        array_unshift($breadcrumb, $root);

        return $breadcrumb;
    }
}
