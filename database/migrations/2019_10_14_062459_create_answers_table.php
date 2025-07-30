<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('questions_id')->unsigned()->nullable();
            $table->foreign('questions_id')->references('id')->on('questions')->nullable();
            $table->string('answer_text')->nullable();
//            $table->bigInteger('options_questions_id')->unsigned()->nullable();
//            $table->foreign('options_questions_id')->references('id')->on('options_questions')->nullable();
            $table->softDeletes();  //add this line
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
        Schema::table('answers_questions', function (Blueprint $table) {
            $table->dropSoftDeletes(); //add this line
        });    }
}
