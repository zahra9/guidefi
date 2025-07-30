<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RatesCardOffers extends Model
{
    protected $table = 'rates_card_offers';
    //
    protected $fillable = ['advisor_id','client_id','sent_time','amount','start_time','end_time','status','rate_card_id','offer_title','price_tag'];


    public function ratecard()
    {
        return $this->belongsTo('App\RateCard','rate_card_id');
    }

    public function advisor()
    {
        return $this->belongsTo('App\User','advisor_id');
    }
}
