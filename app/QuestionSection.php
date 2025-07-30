<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionSection extends Model
{

    protected $table = 'questions_sections';

    public function questionType(){
        return $this->belongsTo('App\QuestionType','questionaire_types_id');
    }

    public function questions(){
        return $this->hasMany('App\Question','questions_sections_id');
    }

}
