<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AdvisorAvailablity extends Model
{
    //
    protected $table = 'appointment_advisor_availability';
    use SoftDeletes; //add this line
    protected $fillable = [
        'advisor_id','day','start_time','end_time','time_zone',
    ];
}
