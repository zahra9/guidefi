<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientSharesFeedbacksAndJournalWithTherapistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_shared_feedback_and_journals_with_therapist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('therapist_id')->nullable();
            $table->bigInteger('feedback_id')->nullable();
            $table->bigInteger('journal_id')->nullable();
            $table->bigInteger('active')->default(0)->nullable();
            $table->enum('type', ['feedback', 'journal']);
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
        Schema::dropIfExists('clients_shared_feedback_and_journal_with_therapist');
    }
}
