<?php

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
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('exam_group_id')->unsigned();
            $table->foreign('exam_group_id')->references('id')->on('exam_groups')->onDelete('cascade');
            $table->string('exam_name',100);
            $table->text('exam_data');
            $table->text('exam_input_file')->nullable();
            $table->text('exam_output_file');
            $table->integer('memory_size');
            $table->float('time_limit',6,5);
            $table->integer('full_score');
            $table->float('cut_wrongans',6,2);
            $table->float('cut_comerror',6,2);
            $table->float('cut_overmemory',6,2);
            $table->float('cut_overtime',6,2);
            $table->text('main_code')->nullable();
            $table->enum('case_sensitive',['0','1']);
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
        Schema::drop('exams');
    }
}
