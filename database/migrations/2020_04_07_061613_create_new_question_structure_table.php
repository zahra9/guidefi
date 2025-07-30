<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewQuestionStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_question_structure', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('question_tag');
            $table->string('client_question_section_name');
            $table->tinyInteger('client_section_order');
            $table->string('client_question');
            $table->integer('client_order_no');
            $table->integer('client_question_weightage');
            $table->string('client_validation_type');
            $table->tinyInteger('is_client');
            $table->tinyInteger('is_therapist');
            $table->string('therapist_question_section_name');
            $table->integer('therapist_section_order');
            $table->string('therapist_question');
            $table->integer('therapist_order_no');
            $table->integer('therapist_question_weightage');
            $table->string('therapist_validation_type');
            $table->integer('scale_question_id');
            $table->string('question_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_question_structure');
    }
}
