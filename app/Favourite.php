<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $table = 'favorite';
    protected $fillable = ['user_id','video_id','quote_id','content_type','is_like','watch_later','favorite'];

    public function video()
    {
        return $this->belongsTo('App\Models\Video','video_id');
    }

    public function quote()
    {
        return $this->belongsTo('App\Models\Quote','quote_id');
    }
}
