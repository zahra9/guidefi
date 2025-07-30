<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Therapist extends Model
{
    //
    protected $table = 'therapists';

    protected $fillable = [
        'fullname', 'certificatelist', 'about','experience','availability','rating','image_url',
    ];
    use SoftDeletes; //add this line

    protected $dates = [
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function appointments()
    {
        return $this->hasMany('App\Appointment','therapist_id');
    }

    public function TherapistClientAppointment()
    {
        return $this->hasMany('App\TherapistClientAppointment','therapist_id');
    }

    public function timing()
    {
        return $this->hasOne('App\Timing');
    }

    public function timings()
    {
        return $this->hasMany('App\TherapistAvailablity');
    }

    public function shareFeedback()
    {
        return $this->belongsToMany('App\clientFeedbackFromTherapist','clients_shared_feedback_and_journals_with_therapist','therapist_id','feedback_id')->withPivot('created_at');
    }

    public function shareJournals()
    {
        return $this->belongsToMany('App\ClientJournal','clients_shared_feedback_and_journals_with_therapist','therapist_id','journal_id')->withPivot('created_at');
    }

     public function clients()
    {
        return $this->hasMany('App\ClientSelectedTherapist','therapist_id');
    }
     public function ratings()
    {
        return $this->hasMany('App\TherapistRatingReviews','therapist_id');
    }
}
