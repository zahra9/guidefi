<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CorporateClientSignupCode extends Model
{
    protected $guarded = [''];
    use SoftDeletes; //add this line
    protected $table = 'corp_client_signup_code';
    protected $fillable = [
        'corp_client_id',
        'sign_up_code',
        'is_used',
        'user_id',
        'created_by'

    ];


}



