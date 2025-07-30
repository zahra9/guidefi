<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;


class CorporateClientPrepaidPayment extends Model
{
    protected $guarded = [''];
    use SoftDeletes; //add this line
    protected $table = 'corp_client_prepaid_payments';
    protected $fillable = [
        'user_id',
        'corp_client_id',
        'payment_date',
        'total_amount',
        'notes'
    ];



    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function corporateclient()
    {
        return $this->belongsTo('App\CorporateClient');
    }

}



