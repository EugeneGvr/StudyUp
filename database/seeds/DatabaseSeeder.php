<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        //run Roles seed
//        $rolesTableSeeder = new RolesTableSeeder();
//        $rolesTableSeeder->run();
//
//        //run Admins seed
//        $adminsTableSeeder = new AdminsTableSeeder();
//        $adminsTableSeeder->run();

//        //run Users seed
//        $usersTableSeeder = new UsersTableSeeder();
//        $usersTableSeeder->run();

        //run Localities seed
        $localitiesTableSeeder = new LocalitiesTableSeeder();
        $localitiesTableSeeder->run();

    }
}
