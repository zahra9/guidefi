<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;


class CorporateClient extends Model
{
    protected $guarded = [''];
    use SoftDeletes; //add this line
    protected $table = 'corporate_clients';
    protected $fillable = [
        'company_name',
        'company_phone',
        'company_address',
        'contact_person_name',
        'contact_person_address',
        'contact_person_phone',
        'user_id',
        'annonymity',
        'password',
        'invitation_link',
        'payment_method',
        'is_corp_email'
    ];
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    public function getPasswordAttribute($value)
    {
        if(!is_null($value) && !empty($value))
        {
            return Crypt::decryptString($value);
        }

    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function corporateclientprepaidpayments()
    {
        return $this->hasMany('App\CorporateClientPrepaidPayment','corp_client_id');
    }

}



