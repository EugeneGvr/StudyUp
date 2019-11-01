<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public static function getRoles()
    {
        $roles = self::orderBy('created_at')->paginate()->only('id', 'name');

        return $roles;
    }

    public static function createRole($params)
    {
//        $roles = self::orderBy('created_at')->paginate()->only('id', 'name');

        return [
            'Status' => 1
        ];
    }
}
