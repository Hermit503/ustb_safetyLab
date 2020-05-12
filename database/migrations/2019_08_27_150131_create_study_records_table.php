<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_records', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('user_id',20);
            $table->foreign('user_id')->references('user_id')->on('users');

            $table->bigInteger('content_id')->unsigned()->comment('contents表的id');
            $table->foreign('content_id')->references('id')->on('study_contents');

            $table->integer('time')->comment('学习时间，按秒计');

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
        Schema::dropIfExists('study_records');
    }
}
