<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientrequest extends Model
{
    protected $table = 'client_requests';

    protected $guarded = ['id'];
//    protected $fillable = ['client_type','description','user_id','client_type', 'status', 'income_level', 'investment_level', 'assets', 'service', 'price'];


    public function user()
    {
//        return $this->hasOne('App\User','id');
        return $this->belongsTo('App\User','user_id');

    }
}
