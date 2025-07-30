<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('questions_id')->unsigned();

            $table->foreign('questions_id')->references('id')->on('questions')->nullable();
            $table->string('label')->nullable();
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
        Schema::table('options_questions', function (Blueprint $table) {
            $table->dropSoftDeletes(); //add this line
        });
    }
}
