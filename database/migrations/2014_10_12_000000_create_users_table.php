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
            $table->bigIncrements('id');

            $table->string('open_id')->nullable()->unique()->comment('微信开放id');
            $table->string('session_key')->nullable()->unique();

            $table->string('user_id',20)->nullable()->unique()->comment('工号');
            $table->string('name');
            $table->enum('sex',['男','女'])->default('男')->nullable();
            $table->string('phone_number',11)->nullable();
            $table->string('title')->nullable()->comment('职称');
            $table->text('unit_name')->nullable()->comment('单位名称，用json存');
            $table->string('roles')->nullable()->comment('身份');
            $table->string('nickname')->nullable()->comment('微信昵称');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
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
