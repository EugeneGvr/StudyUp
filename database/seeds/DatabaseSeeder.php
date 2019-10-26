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
        $seeder = new AdminsTableSeeder();
        $seeder->run();

        //run Users seed
        $seeder = new UsersTableSeeder();
        $seeder->run();

        //run Localities seed
        $seeder = new LocalitiesTableSeeder();
        $seeder->run();

    }
}
