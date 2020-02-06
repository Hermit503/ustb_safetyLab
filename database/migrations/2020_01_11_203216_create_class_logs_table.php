<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('experimentalName')->nullable()->comment("实验名称");
            $table->string('buildingName')->nullable()->comment("教学楼名称");
            $table->string('classroomNum')->nullable()->comment("实验室号");
            $table->string('className')->nullable()->comment("系别");
            $table->string('classNum')->nullable()->comment("班级号");
            $table->string('studentNum')->nullable()->comment("上课人数");
            $table->string('status')->nullable()->comment("状态");
            $table->string('teacherName')->nullable()->comment("教师姓名");
            $table->string('phoneNum')->nullable()->comment("手机号");
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
        Schema::dropIfExists('class_logs');
    }
}
