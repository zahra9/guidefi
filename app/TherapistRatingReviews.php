<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TherapistRatingReviews extends Model
{
    //
    protected $table = 'therapist_rating_reviews';

    protected $guarded = [''];

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

    public function therapist()
    {
        return $this->belongsTo('App\Therapist','therapist_id');
    }
}
