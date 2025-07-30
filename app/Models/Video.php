<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;
    protected $table = 'videos';
    protected $fillable =
        ['title','thumbnail','url','user_id','views','video_id','admin_status','video_status'];

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
        return $this->hasMany('App\Models\Video','video_id');
    }

    public function views(){
        return $this->hasMany('App\VideoView');
    }
}