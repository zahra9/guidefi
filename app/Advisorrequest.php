<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advisorrequest extends Model
{
    protected $table = 'advisor_requests';

    protected $fillable = ['user_id','status','request_date','approval_date', 'company_name', 'company_logo', 'finance_link', 'licence', 'area_of_experties', 'compensation_type','age_requested'];
    protected $guarded = ['id'];


    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
