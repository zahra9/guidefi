<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Appointment extends Model
{
    //

    use SoftDeletes; //add this line
    protected $fillable = [
        'therapists_id', 'users_id','datetime','appointment_number','timeslot_number','is_archive','type','reason','client_timezone','therapist_timezone'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function therapist(){
        return $this->belongsTo('App\Therapist','therapist_id');
    }
}
