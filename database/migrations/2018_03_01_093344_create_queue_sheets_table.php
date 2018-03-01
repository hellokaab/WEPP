<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('res_sheet_id')->unsigned();
            $table->foreign('res_sheet_id')->references('id')->on('res_sheets')->onDelete('cascade');
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
        Schema::drop('queue_sheets');
    }
}
