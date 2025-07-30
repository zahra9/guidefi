<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('questions_sections_id')->unsigned()->nullable();
            $table->foreign('questions_sections_id')->references('id')->on('questions_sections')->nullable();
            $table->string('question')->nullable();
            $table->string('validation_type')->default('optional');
            $table->integer('order_no')->nullable();
            $table->enum('question_type', array('Text','Radio','Checkbox','Dropdown'))->nullable();
            $table->timestamps();
            $table->softDeletes();  //add this line
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropSoftDeletes(); //add this line
        });
    }
}
