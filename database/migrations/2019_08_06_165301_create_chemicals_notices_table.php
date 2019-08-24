<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChemicalsNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chemicals_notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->comment('所属单位id');
            $table->string("user_id_1")->comment("提交者id");
            $table->string("user_name_1")->comment("提交者姓名");
            $table->string("user_id_2")->nullable()->comment("确认者id");
            $table->string("user_name_2")->comment("确认者姓名");
            $table->string("chemical_id")->comment("药品id");
            $table->string("chemical_name")->comment("药品名字");
            $table->integer("stock")->comment("数量");
            $table->string("remarks")->nullable()->comment("备注");
            $table->enum("isConfirm_1",[0,1])->default(1)->comment("1是否确认");
            $table->enum("isConfirm_2",[0,1])->default(0)->comment("2是否确认");
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
        Schema::dropIfExists('chemicals_notices');
    }
}
