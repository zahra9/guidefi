<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TimeSlots extends Model
{
    //

    protected $table = 'appointment_time_slots';
    use SoftDeletes; //add this line
    protected $fillable = [
        'label','start_time','end_time','time_slot_template_id',
    ];
}
