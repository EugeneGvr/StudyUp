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

        // accounts
        $eugene = User::create([
            'account_id' => $account->id,
            'first_name' => 'Eugene',
            'last_name' => 'Havrylov',
            'email' => 'link6596@gmail.com',
            'owner' => true,
            'password' => 'ubuntu123'

        ]);

        $ivan = User::create([
            'account_id' => $account->id,
            'first_name' => 'Ivan',
            'last_name' => 'Velykyi',
            'email' => 'velikiy300@gmail.com',
            'owner' => true,
            'password' => 'ubuntu123'
        ]);
    }
}
