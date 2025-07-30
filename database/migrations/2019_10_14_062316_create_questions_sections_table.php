<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('questionaire_types_id')->unsigned();
            $table->index('questionaire_types_id');
            $table->string('label', 255)->nullable();
            $table->longText('description')->nullable();
            $table->integer('order_no')->nullable();
            $table->softDeletes();  //add this line
            $table->timestamps();

        });
        Schema::table('questions_sections', function (Blueprint $table) {
            $table->foreign('questionaire_types_id')->references('id')->on('questionaire_types');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions_sections', function (Blueprint $table) {
            $table->dropSoftDeletes(); //add this line
        });
    }
}
