<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHiddensLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hiddens_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('hidden_id')->unsigned()->nullable()->comment('隐患id');
            $table->foreign('hidden_id')->references('id')->on('hiddens')->onDelete('set null');
            $table->string('user_name')->comment('名字');
            $table->string('reason')->comment('原因');
            $table->string('image')->nullable()->comment('图片路径');
            $table->enum('isSolve',[0,1])->default(0)->comment('是否是解决的人');
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
        Schema::dropIfExists('hiddens_logs');
    }
}
