<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

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
        Log::stack(['errorlog', 'slack'])->info('Admin "Евгений Гаврилов" is added');

        Admin::create([
            'first_name'    => 'Ivan',
            'last_name'     => 'Velykyi',
            'email'         => 'velikiy300@gmail.com',
            'password'      => Hash::make('ubuntu123'),
            'role_id'       => 1,
        ]);
        Log::stack(['errorlog', 'slack'])->info('Admin "Ivan Velykyi" is added');
    }
}
