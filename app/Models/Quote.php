<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;
    protected $table = 'quotes';

    protected $fillable = ['title','image_url','description','user_id','views','quote_status','admin_status'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function favourite()
    {
        return $this->hasMany('App\Models\Quote','quote_id');
    }


}
