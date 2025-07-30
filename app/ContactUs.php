<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    //
    protected $table = 'contact_us';

    protected $guarded = [''];
//    use SoftDeletes;

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
