<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timing extends Model
{
    use SoftDeletes; //add this line

    protected $table = 'timings';
    protected $fillable = [
       'timezone', 'monday_start', 'monday_end','monday_status','tuesday_start', 'tuesday_end','tuesday_status','wednesday_start', 'wednesday_end','wednesday_status',
       'thursday_start', 'thursday_end','thursday_status','friday_start', 'friday_end','friday_status','saturday_start', 'saturday_end','saturday_status','sunday_start', 'sunday_end','sunday_status','is_active'

    ];

    public function therapist()
    {
        return $this->belongsTo('App\Therapist','therapist_id');
    }
}
