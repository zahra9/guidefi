<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoView extends Model
{
    protected $table = 'video_view';
    protected $fillable = ['user_id','video_id'];

    public function video(){
        return $this->belongsTo('App\Models\Video');
    }
}
