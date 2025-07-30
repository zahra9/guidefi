<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('therapist_id')->unsigned()->nullable();
            $table->foreign('therapist_id')->references('id')->on('therapists')->nullable();
            $table->Integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->integer('timeslot_number')->nullable();
            $table->boolean('is_archive')->defualt(0);
            $table->enum('type',array('Audio','Video','Text'))->nullable();
            $table->string('reason')->nullable();
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

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropSoftDeletes(); //add this line
        });
    }
}
