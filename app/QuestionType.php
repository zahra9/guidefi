<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    protected $table = 'questionaire_types';

    public function questionSection(){
        return $this->hasMany('App\QuestionSection','questionaire_types_id');
    }
}
