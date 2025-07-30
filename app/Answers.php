<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    //
    protected $table = 'answers';

    protected $guarded = [''];
//    use SoftDeletes;

    public function question(){
        return $this->belongsTo('App\Question','question_id');
    }

    public function options(){
        return $this->hasMany('App\AnswerOption','answer_id');
    }

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

    public function ratecard(){
        return $this->belongsTo('App\RateCard','rate_card_id');
    }
}
