<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    protected $table = 'notes';
    protected $fillable = ['client_id','title','description'];
}
