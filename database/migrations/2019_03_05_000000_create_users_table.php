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
            $table->string('first_name', 25)->nullable();
            $table->string('last_name', 25)->nullable();
            $table->string('username')->unique();
            $table->string('city_id')->nullable();
            $table->string('email', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_link', 25)->nullable();
            $table->string('invite_link', 25)->nullable();
            $table->string('password');
            $table->string('photo_path', 100)->nullable();
            $table->enum('status', config('app')['user_statuses'])->default(config('app')['user_statuses'][0]);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
