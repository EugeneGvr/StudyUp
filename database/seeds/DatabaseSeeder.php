<?php

use App\User;
use App\Account;
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
        $account = Account::create(['name' => 'Acme Corporation']);

        //run Admins seed
        $adminsTableSeeder = new AdminsTableSeeder();
        $adminsTableSeeder->run();

        //run Users seed
        $usersTableSeeder = new UsersTableSeeder();
        $usersTableSeeder->run();

        //run Roles seed
        $rolesTableSeeder = new RolesTableSeeder();
        $rolesTableSeeder->run();

        //run Localities seed
        //$localitiesTableSeeder = new LocalitiesTableSeeder();
        //$localitiesTableSeeder->run();

    }
}
