<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    public static function getLocalities($params = [])
    {
        if(empty($params)) {
            $params = [
                'type' => 'О',
            ];
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

        return $localities;
    }
}
