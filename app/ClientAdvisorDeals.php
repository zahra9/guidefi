<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAdvisorDeals extends Model
{
    use SoftDeletes;
    protected $table = 'client_advisor_deals';
    protected $guarded = ['id'];



    public function advisor()
    {
        return $this->belongsTo('App\User','advisor_id');
    }

    public function client()
    {
        return $this->belongsTo('App\User','client_id');
    }

    public function match()
    {
        return $this->belongsTo('App\MatchedAdvisorList','match_id');
    }

    public function offer()
    {
        return $this->belongsTo('App\RatesCardOffers','offer_id');
    }
    public function appointment()
    {
        return $this->belongsTo('App\AppointmentAadvisorClient','appointment_advisor_client_id');
    }
}
