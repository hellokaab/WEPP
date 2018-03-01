<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('personal_id',20);
            $table->string('prefix',30);
            $table->string('fname_en');
            $table->string('fname_th');
            $table->string('lname_en');
            $table->string('lname_th');
            $table->string('stu_id',20)->nullable();
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
            $table->string('email')->nullable();
            $table->char('user_type');
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
        Schema::drop('users');
    }
}
