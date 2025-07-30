<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TimeSlotsFormat extends Model
{
    //
    protected $table = 'appointment_time_slots_format';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    use SoftDeletes; //add this line
    protected $fillable = [
        'formal_label'
    ];
}
