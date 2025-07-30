<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackDealFinish extends Model
{
    //
    protected $table = 'feedback_deal_finish';

    protected $guarded = [''];

    public function client(){
        return $this->belongsTo('App\User','client_id');
    }

    public function advisor(){
        return $this->belongsTo('App\User','advisor_id');
    }

}
