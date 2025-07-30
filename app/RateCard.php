<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateCard extends Model
{
    use SoftDeletes;
    protected $table = 'rate_cards';

    protected $guarded = [];


    /*
     * This is a Request for the advisor
     * */
    public function answers()
    {
        // This table has been removed.
        return $this->hasMany('App\Answers');
    }
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];
}
