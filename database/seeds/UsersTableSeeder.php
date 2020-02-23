<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id'            => 1,
            'first_name'    => 'Eugene',
            'last_name'     => 'Havrylov',
            'username'      => 'eugene_gvr',
            'email'         => 'link6596@gmail.com',
            'password'      =>  Hash::make('ubuntu123'),
            'city_id'       => 11831

        ]);
        Log::stack(['errorlog', 'slack'])->info('User "Евгений Гаврилов" is added');

        User::create([
            'id'            => 2,
            'first_name'    => 'Ivan',
            'last_name'     => 'Velykyi',
            'username'      => 'i-velykyi',
            'email'         => 'velikiy300@gmail.com',
            'password'      => Hash::make('ubuntu123'),
            'city_id'       => 11831
        ]);
        Log::stack(['errorlog', 'slack'])->info('User "Ivan Velykyi" is added');
    }
}
