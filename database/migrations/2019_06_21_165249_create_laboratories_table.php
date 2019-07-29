<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaboratoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('building_name')->comment('教学楼名称');
            $table->string('classroom_num')->comment('教室名称');
            $table->string('laboratory_name')->comment('实验室名称');

            $table->bigInteger('safety_head')->unsigned()->comment('实验室安全负责人');
            $table->foreign('safety_head')->references('id')->on('users');

            $table->bigInteger('maintenance_people1')->unsigned()->comment('实验室维护人');
            $table->foreign('maintenance_people1')->references('id')->on('users');

            $table->bigInteger('maintenance_people2')->unsigned()->comment('实验室维护人');
            $table->foreign('maintenance_people2')->references('id')->on('users');

            $table->enum('laboratory_attribute',['自建','共建'])->comment('实验室属性');
            $table->enum('laboratory_status',['批复建设','施工在建','建成使用','废弃不用'])->comment('实验室状态');

            $table->string('laboratory_type')->comment('实验室类别');

            $table->bigInteger('unit_id')->unsigned()->comment('实验室管理单位id');
            $table->foreign('unit_id')->references('id')->on('units');

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
        Schema::dropIfExists('laboratories');
    }
}
