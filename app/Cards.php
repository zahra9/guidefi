<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Cards extends Model
{
    protected $table = 'client_cards';

    protected $guarded = [];

    protected $protected = ['cvv_number'];


    public function setCvvNumberAttribute($value)
    {
        $this->attributes['cvv_number'] = Crypt::encryptString($value);
    }

    public function setCardNumberAttribute($value)
    {
        $this->attributes['card_number'] = Crypt::encryptString($value);
    }

    public function setExpiryMonthAttribute($value)
    {
        $this->attributes['expiry_month'] = Crypt::encryptString($value);
    }

    public function setExpiryYearAttribute($value)
    {
        $this->attributes['expiry_year'] = Crypt::encryptString($value);
    }

    public function getCardNumberAttribute($value)
    {
        return str_pad(substr(Crypt::decryptString($value), -4), strlen(Crypt::decryptString($value)), '*', STR_PAD_LEFT);
    }

    public function getCardAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getCvvNoAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getExpiryMonthAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getExpiryYearAttribute($value)
    {
        return Crypt::decryptString($value);
    }



    public function client(){
        return $this->belongsTo('App\User', 'client_id');
    }
}
