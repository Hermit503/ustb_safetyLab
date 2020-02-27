<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_manages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('unit_id')->unsigned()->comment('所在单位id');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->string('aqts')->comment('安全通识题目数量');
            $table->string('dqaq')->comment('电气题目数量');
            $table->string('hxp')->comment('化学题目数量');
            $table->string('jxjz')->comment('机械建筑题目数量');
            $table->string('tzsb')->comment('特种设备题目数量');
            $table->string('xfaq')->comment('消防安全题目数量');
            $table->string('yxsw')->comment('医学生物安全题目数量');
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
        Schema::dropIfExists('exam_manages');

    }
}
