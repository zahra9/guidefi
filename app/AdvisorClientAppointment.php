<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AdvisorClientAppointment extends Model
{
    //


  protected $table = 'appointment_advisor_client';
    use SoftDeletes; //add this line
    protected $fillable = [
        'match_id', 'is_discovery_call', 'call_status', 'client_service_id', 'advisor_id', 'advisor_timezone', 'client_id','client_timezone','appointment_status','appointment_date','start_time','end_time','appointment_day','type','appointment_number','timeslot_number'
    ];
    public function client()
    {
        return $this->belongsTo('App\User','client_id');
    }
    public function advisor(){
        return $this->belongsTo('App\User','advisor_id');
    }
}
