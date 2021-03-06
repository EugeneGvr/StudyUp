<?php

namespace App\Models;

use League\Glide\Server;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use App\Traits\StringGenerator;
use Illuminate\Support\Facades\Mail;

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

    public function getAdmin($id)
    {
        $admin = self::find($id);

        if(!$admin) {
            return 'No admin with such id';
        }

        $photo_path = $this->getFilePublicUrl(
            $admin->photo_path,
            config('filesystems')['avatars']['admins']['path']
        );

        return [
            'id'            => $admin->id,
            'first_name'    => $admin->first_name,
            'last_name'     => $admin->last_name,
            'email'         => $admin->email,
            'phone'         => $admin->phone,
            'role'          => $admin->role_id,
            'photo_path'    => $photo_path,
        ];
    }

    public function addAdmin($params)
    {
        $avatarConfig = config('filesystems')['avatars'];

        if ($params['password_auto_generation'] && empty($params['password'])) {
            $params['password'] = $this->generateString();
        }

        DB::beginTransaction();
            try {
                $admin = $this;

                $admin->first_name  = $params['first_name'];
                $admin->last_name   = $params['last_name'];
                $admin->email       = $params['email'];
                $admin->phone       = $params['phone'];
                $admin->role_id     = $params['role'];
                $admin->password    = Hash::make($params['password']);
                $admin->save();

                $admin->photo_path = !empty($params['photo']) ?
                    $this->uploadFile($params['photo'], $admin->id, $avatarConfig['admins']['path']) :
                    null;
                $admin->save();

                DB::commit();


                //send mail to the admin email
                $to_name = $admin->first_name;
                $to_email = 'velikiy300@gmail.com';
                $data = array('name'=>$admin->first_name.' '.$admin->last_name, "body" => "Test mail");

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

    public function updateAdmin($id, $params)
    {
        try {
            $admin = $this->find($id);

            if (!$admin) {
                throw new \Exception("Admin not found");
            }
            $avatarConfig = config('filesystems')['avatars'];

            DB::beginTransaction();
            $admin->first_name  = $params['first_name'];
            $admin->last_name   = $params['last_name'];
            $admin->email       = $params['email'];
            $admin->phone       = $params['phone'];
            $admin->role_id     = $params['role'];

            if (!empty($params['photo'])) {
                if (!empty($admin->photo_path)) {
                    $this->deleteFile($admin->photo_path);
                }
                $admin->photo_path = $this->uploadFile($params['photo'], $id, $avatarConfig['admins']['path']);
            }
            $admin->save();

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
