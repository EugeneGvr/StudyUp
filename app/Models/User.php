<?php

namespace App\Models;

use App\Traits\StringGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use League\Glide\Server;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use SoftDeletes, Authenticatable, Authorizable, StringGenerator;

    protected $casts = [
        'owner' => 'boolean',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function photoUrl(array $attributes)
    {
        if ($this->photo_path) {
            return URL::to(App::make(Server::class)->fromPath($this->photo_path, $attributes));
        }
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function scopeWhereRole($query, $role)
    {
        switch ($role) {
            case 'user': return $query->where('owner', false);
            case 'owner': return $query->where('owner', true);
        }
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
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public static function getUsers()
    {
        $users = self::orderBy('created_at')->paginate()->only('id', 'first_name', 'last_name', 'email', 'city_id');

        return $users;
    }

    public function addUser($params)
    {
        $avatarConfig = config('filesystems')['avatars'];

        if ($params['password_auto_generation'] && empty($params['password'])) {
            $params['password'] = $this->generateString();
        }

        DB::beginTransaction();
        try {
            $user = $this;

            $user->first_name  = $params['first_name'];
            $user->last_name   = $params['last_name'];
            $user->email       = $params['email'];
            $user->password    = Hash::make($params['password']);
            if (empty($params['phone'])) {
                $user->phone    = $params['phone'];
            }
            $user->save();

            $user->photo_path = !empty($params['photo']) ?
                $this->uploadFile($params['photo'], $user->id, $avatarConfig['users']['path']) :
                null;
            $user->save();

            DB::commit();


            //send mail to the user email
            $to_name = $user->first_name;
            $to_email = 'velikiy300@gmail.com';
            $data = array('name'=>$user->first_name.' '.$user->last_name, "body" => "Test mail");

            Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                    ->subject('Welcome');
                $message->from('studyup200@gmail.com','Study Up');
            });
            //
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' =>'Something went wrong during creating an administrator',
            ];
        }

        return [
            'status' => 1
        ];
    }

    public function updateUser($id, $params)
    {
        try {
            $user = $this->find($id);

            if (!$user) {
                throw new \Exception("Admin not found");
            }
            $avatarConfig = config('filesystems')['avatars'];

            DB::beginTransaction();
            $user->first_name  = $params['first_name'];
            $user->last_name   = $params['last_name'];
            $user->email       = $params['email'];
            $user->phone       = $params['phone'];

            if (!empty($params['photo'])) {
                if (!empty($user->photo_path)) {
                    $this->deleteFile($user->photo_path);
                }
                $user->photo_path = $this->uploadFile($params['photo'], $id, $avatarConfig['admins']['path']);
            }
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 0,
                'message' =>'Something went wrong during updating an administrator. Message: ['.$e->getMessage().']',
            ];
        }

        return [
            'status' => 1
        ];
    }
}
