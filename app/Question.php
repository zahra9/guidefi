<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    protected $table = 'questions';
//    use SoftDeletes; //add this line


    public function section(){
        return $this->belongsTo('App\QuestionSection','questions_sections_id');
    }

    public function answer(){
        return $this->hasOne('App\Answers','question_id');
    }

    public function options(){
        return $this->hasMany('App\Options','question_id');
    }

}
