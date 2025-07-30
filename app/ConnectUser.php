<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConnectUser extends Model
{
    protected $table = 'connect_users';
    protected $fillable = ['name','email','password','phone','financial_professional','interested_in','mail_list'];
}
