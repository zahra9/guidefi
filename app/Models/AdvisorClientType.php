<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvisorClientType extends Model
{
    /**
* The database table used by the model.
*
* @var string
     */
    protected $table = 'advisor_client_type';

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'client_type','user_id'
    ];

    public function user()
    {
//        return $this->hasOne('App\User','id');
        return $this->belongsTo('App\User','user_id');

    }
}
