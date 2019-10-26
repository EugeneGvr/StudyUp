<?php

use App\Admin;
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
            'account_id'    => 1,
            'first_name'    => 'Евгений',
            'last_name'     => 'Гаврилов',
            'email'         => 'link6596@gmail.com',
            'password'      => 'ubuntu123'
        ]);

        Admin::create([
            'account_id'    => 1,
            'first_name'    => 'Ivan',
            'last_name'     => 'Velykyi',
            'email'         => 'velikiy300@gmail.com',
            'password'      => 'ubuntu123'
        ]);
    }
}
