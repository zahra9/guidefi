<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','notification_text','sender_id','receiver_id','type','title','description','role'
    ];


    /**
     * Dates
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    //relation with user
    public function sender(){
        return $this->belongsTo(User::class,'sender_id');
    }
//relation with user
    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id');
    }
}
