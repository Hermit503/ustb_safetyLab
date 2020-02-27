<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHiddensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hiddens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id',20);
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->enum('type',['hidden','issue'])->default('hidden')->comment('类型');
            $table->string('position')->nullable()->comment('位置');
            $table->string('title')->nullable()->comment('标题');
            $table->string('detail')->nullable()->comment('详情');
            $table->date('occurrence_time')->nullable()->comment('发现时间');
            $table->string('image')->nullable()->comment('照片url');
            $table->enum('isSolve',[1,0])->default(0)->nullable()->comment('解决状态');
            $table->string("number")->nullable()->comment("资产编号或化学品id");
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
        Schema::dropIfExists('hiddens');
    }
}
