<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StripePaymentLog extends Model
{
    protected $guarded = [''];
    use SoftDeletes; //add this line

    public function user()
    {
        return $this->belongsTo('App\User','advisor_id');
    }

}
