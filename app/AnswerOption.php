<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerOption extends Model
{
    protected $table = 'answer_options';

    protected $guarded = [''];

    public function answer(){
        return $this->belongsTo('App\Answers','answer_id');
    }

    public function option(){
        return $this->belongsTo('App\Options','option_id');
    }

}
