<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('标题');
            $table->text('users')->comment('通知对象');
            $table->text('comment')->comment('通知概述');
            $table->string('pictures')->nullable()->comment('通知图片');
            $table->string('file')->nullable()->comment('附件');

            $table->string('build_id')->comment('创建人工号');
            $table->foreign('build_id')->references('user_id')->on('users');

            $table->text('received_users')->nullable()->comment('已收到的人');

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
        Schema::dropIfExists('notices');
    }
}
