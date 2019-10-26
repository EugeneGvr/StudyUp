<?php

use App\User;
use Illuminate\Database\Seeder;

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
            'account_id'    => 1,
            'first_name'    => 'Eugene',
            'last_name'     => 'Havrylov',
            'username'      => 'eugene_gvr',
            'email'         => 'link6596@gmail.com',
            'password'      => 'ubuntu123'

        ]);

        User::create([
            'account_id'    => 1,
            'first_name'    => 'Ivan',
            'last_name'     => 'Velykyi',
            'username'      => 'i-velykyi',
            'email'         => 'velikiy300@gmail.com',
            'password'      => 'ubuntu123'
        ]);
    }
}
