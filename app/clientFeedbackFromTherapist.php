<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clientFeedbackFromTherapist extends Model
{
    //
    protected $table = 'client_feedback_from_therapist';

    protected $guarded = [''];
//    use SoftDeletes;

    public function therapist()
    {
        return $this->belongsTo('App\Therapist','therapist_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function clientSharedFeedback()
    {
        return $this->belongsToMany('App\User','clients_shared_feedback_and_journals_with_therapist','client_id','feedback_id');
    }

    public function therapistSharedFeedback()
    {
        return $this->belongsToMany('App\Therapist','clients_shared_feedback_and_journals_with_therapist','therapist_id','feedback_id');
    }
}
