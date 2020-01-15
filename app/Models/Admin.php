<?php

namespace App\Models;

use League\Glide\Server;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use App\Traits\StringGenerator;

class Admin extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, StringGenerator;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'photo', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function photoUrl(array $attributes)
    {
        if ($this->photo_path) {
            return URL::to(App::make(Server::class)->fromPath($this->photo_path, $attributes));
        }
    }

    public function orderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereRole($role);
        })->when($filters['locality'] ?? null, function ($query, $locality) {
                $query->withLocality($locality);
        });
    }

    public static function getAdmins()
    {
        $administrators = self::orderBy('created_at')->paginate()->only('id', 'first_name', 'last_name', 'email');


        return $administrators;
    }

    public static function getAdmin($id)
    {
        $admin = self::find($id);

        if(!$admin) {
            return 'No admin with such id';
        }

        return [
            'id'            => $admin->id,
            'first_name'    => $admin->first_name,
            'last_name'     => $admin->last_name,
            'email'         => $admin->email,
            'phone'         => $admin->phone,
            'role_id'       => $admin->role_id,
            'photo'         => $admin->photoUrl(['w' => 60, 'h' => 60, 'fit' => 'crop']),
        ];
    }

    public function addAdmin($params)
    {
        try {
            $admin = $this;
            $password = $params['password_auto_generation'] && empty($params['password']) ?
                $this->generateString() :
                $params['password'];

            $admin->first_name = $params['first_name'];
            $admin->second_name = $params['second_name'];
            $admin->email = $params['email'];
            $admin->phone = $params['phone'];
            $admin->role_id = $params['role_id'];
            $admin->password = $password;
            $admin->save();
        } catch (\Exception $e) {
            return 'Something went wrong during creating an administrator';
        }
error_log(print_r('ok', 1));
        return [
            'status' => 1
        ];
    }
}
