<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'first_name'    => 'Евгений',
            'last_name'     => 'Гаврилов',
            'email'         => 'link6596@gmail.com',
            'password'      => Hash::make('ubuntu123'),
            'role_id'       => 1,
        ]);
//        success("Admin created");

        Admin::create([
            'first_name'    => 'Ivan',
            'last_name'     => 'Velykyi',
            'email'         => 'velikiy300@gmail.com',
            'password'      => Hash::make('ubuntu123'),
            'role_id'       => 1,
        ]);
    }
}
