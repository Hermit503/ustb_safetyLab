<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChemicalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chemicals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('chemical_id')->unique()->comment('危化品id编号');
            $table->bigInteger('unit_id')->unsigned()->comment('所在单位id');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->string('user_id',20)->comment('管理员');
            $table->bigInteger('laboratory_id')->unsigned()->comment('实验室id');
            $table->foreign('laboratory_id')->references('id')->on('laboratories');
            $table->enum('status',['正常','存在问题'])->nullable()->comment('状态');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->string('monitor_id',20)->comment('管理者');
            $table->enum('type',['medcine','chemical'])->comment('类型');
            $table->string('name',40)->comment('药品名称');
            $table->string('CAS',20)->comment('CAS编号');
            $table->double('stock')->comment('库存量');
            $table->enum('unit_type',['g','kg','mL','L'])->comment('计量单位');
            $table->string('remarks')->nullable()->comment('备注');
            $table->date('fix_time')->nullable()->comment('最近检修时间');
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
        Schema::dropIfExists('chemicals');
    }
}
