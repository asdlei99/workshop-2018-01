<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('nickname');
            $table->string('password')->nullable();
            $table->string("head_img")->nullable();
            $table->tinyInteger("user_group")->default("3");
            $table->string("phone")->nullable();
            $table->string("qq")->nullable();
            $table->boolean("phone_access")->default(false);
            $table->boolean("email_access")->default(true);
            $table->boolean("qq_access")->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
