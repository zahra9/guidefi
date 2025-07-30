<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientJournal extends Model
{
    protected $table = 'client_journals';
    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo('App\User','client_id');
    }

    public function clientSharedJournals()
    {
        return $this->belongsToMany('App\User','clients_shared_feedback_and_journals_with_therapist','client_id','journal_id');
    }

    public function therapistSharedJournals()
    {
        return $this->belongsToMany('App\Therapist','clients_shared_feedback_and_journals_with_therapist','therapist_id','journal_id');
    }
}
