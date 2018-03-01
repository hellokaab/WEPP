<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_exams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('path_exam_id')->unsigned();
            $table->foreign('path_exam_id')->references('id')->on('path_exams')->onDelete('cascade');
            $table->string('file_type',10);
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
        Schema::drop('queue_exams');
    }
}
