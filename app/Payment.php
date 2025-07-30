<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    protected $guarded = [''];
    use SoftDeletes; //add this line


    public function plan(){
        return $this->belongsTo('App\Package','plan_id');
    }
}
