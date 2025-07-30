<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->float('price',8, 2)->nullable();
            $table->enum('package_type',array('Monthly','Yearly'))->default('Monthly')->nullable();
            $table->enum('text_limit',array('Limited','Unlimited'))->default('Unlimited')->nullable();
            $table->integer('voice_call_limit')->nullable();
            $table->integer('video_call_limit')->nullable();
             $table->integer('voice_call_duration')->nullable();
            $table->integer('video_call_duration')->nullable();
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
        Schema::table('packages', function (Blueprint $table) {
            $table->dropSoftDeletes(); //add this line
        });
    }
}
