<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->boolean('owner')->default(false);
            $table->string('photo_path', 100)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        $eugene = User::create([
            'account_id' => 1,
            'first_name' => 'Eugene',
            'last_name' => 'Havrylov',
            'email' => 'link6596@gmail.com',
            'owner' => true,
            'password' => 'ubuntu123'

        ]);

        $ivan = User::create([
            'account_id' => 1,
            'first_name' => 'Ivan',
            'last_name' => 'Velykyi',
            'email' => 'velikiy300@gmail.com',
            'owner' => true,
            'password' => 'ubuntu123'
        ]);
    }
}
