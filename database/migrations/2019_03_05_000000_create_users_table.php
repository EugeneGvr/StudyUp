<?php

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id')->index();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('city_id')->nullable();
            $table->string('username')->unique();
            $table->string('email', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('invite_link', 25)->nullable();
            $table->string('password');
            $table->string('photo_path', 100)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
