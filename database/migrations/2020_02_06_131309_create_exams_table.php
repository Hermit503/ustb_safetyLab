<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->comment('类型');
            $table->string('question')->comment('题目');
            $table->string('option1')->comment('选项1');
            $table->string('option2')->comment('选项2');
            $table->string('option3')->nullable()->comment('选项3');
            $table->string('option4')->nullable()->comment('选项4');
            $table->string('answer')->comment('答案');

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
        Schema::dropIfExists('exams');
    }
}
