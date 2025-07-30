<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscoverCallLogs extends Model
{
    protected $table = 'discovery_call_logs';
    protected $guarded = [''];


    public function client(){
        return $this->belongsTo('App\User','client_id');
    }

    public function advisor(){
        return $this->belongsTo('App\User','advisor_id');
    }

    // public function user(){
    //     return $this->belongsTo('App\User');
    // }

    public function appointment(){
        return $this->belongsTo('App\AppointmentAadvisorClient','appointment_id');
    }

}
