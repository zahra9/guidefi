<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientSelectedAdvisor extends Model
{
    protected $table = 'client_selected_advisor';

    protected $guarded = [''];

    public function advisor(){
        return $this->belongsTo('App\Therapist','therapist_id');
    }

    public function client(){
        return $this->belongsTo('App\User','user_id');
    }
}
