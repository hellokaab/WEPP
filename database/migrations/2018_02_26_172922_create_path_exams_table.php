<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('path_exams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('res_exam_id')->unsigned();
            $table->foreign('res_exam_id')->references('id')->on('res_exams')->onDelete('cascade');
            $table->text('path');
            $table->char('status')->nullable();
            $table->text('res_run')->nullable();
            $table->dateTime('send_date_time');
            $table->string('file_type',10);
            $table->float('time',6,5)->nullable();
            $table->float('memory',6,2)->nullable();
            $table->string('ip',30);
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
        Schema::drop('path_exams');
    }
}
