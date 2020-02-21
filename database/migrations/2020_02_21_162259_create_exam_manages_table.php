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
            $table->timestamps();
            $table->string('unit_id')->unique()->comment('单位id');
            $table->string('aqts')->comment('安全通识题目数量');
            $table->string('dqaq')->comment('电气题目数量');
            $table->string('hxp')->comment('化学题目数量');
            $table->string('jxjz')->comment('机械建筑题目数量');
            $table->string('tzsb')->comment('特种设备题目数量');
            $table->string('xfaq')->comment('消防安全题目数量');
            $table->string('yxsw')->comment('医学生物安全题目数量');
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
