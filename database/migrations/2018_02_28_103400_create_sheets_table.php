<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('sheet_group_id')->unsigned();
            $table->foreign('sheet_group_id')->references('id')->on('sheet_groups')->onDelete('cascade');
            $table->string('sheet_name');
            $table->string('objective')->nullable();
            $table->string('theory')->nullable();
            $table->string('notation')->nullable();
            $table->string('sheet_trial');
            $table->string('sheet_input_file')->nullable();
            $table->string('sheet_output_file');
            $table->text('main_code')->nullable();
            $table->enum('case_sensitive',['0','1']);
            $table->integer('full_score');
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
        Schema::drop('sheets');
    }
}
