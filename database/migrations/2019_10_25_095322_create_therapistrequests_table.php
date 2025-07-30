<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTherapistrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('therapistrequests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname',255)->nullable();
            $table->string('email',255)->nullable();
            $table->string('address',255)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('webaddress',25)->nullable();
            $table->string('education',50)->nullable();
            $table->string('licensedetail',255)->nullable();
            $table->string('states',255)->nullable();
            $table->string('contactinformation',255)->nullable();
            $table->string('videocounseling',255)->nullable();
            $table->string('counselingonline',255)->nullable();
            $table->string('timeatayana',255)->nullable();
            $table->string('computerdetail',255)->nullable();
            $table->string('osonphone',255)->nullable();
            $table->string('experience',100)->nullable();
            $table->string('atleatexperience',100)->nullable();
            $table->string('investigation',255)->nullable();
            $table->string('disciplineaction',255)->nullable();
            $table->string('otherlanguages',255)->nullable();
            $table->string('counselingstyle',255)->nullable();
            $table->string('modalities',255)->nullable();
            $table->string('specialties',255)->nullable();
            $table->string('experiencewithgay',255)->nullable();
            $table->string('acceptinsurance',255)->nullable();
            $table->string('hearabout',255)->nullable();
            $table->string('cvfile',255)->nullable();
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
        Schema::dropIfExists('therapistrequests');
    }
}
