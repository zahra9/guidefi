<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchedAdvisorList extends Model
{
    //
      protected $table = 'matched_advisor_list';
//      protected $fillable = ['client_id','advisor_id','status','request_date','decline_reason','further_comments','notification_send'];

      protected $guarded = ['id'];

      // client_request_status
      // client_request_date
      // advisor_request_status
      // advisor_request_date
      //0= matched, 1= paid, 2= accepted, 3= rejected


    /**
     * Each Record belongs to users for advisor and client
     *
     * @return mixed
     */
    public function advisor(){
        return $this->belongsTo('App\User','advisor_id');
    }

    /**
     * Each Record belongs to users for advisor and client
     *
     * @return mixed
     */

    public function client()
    {
        return $this->belongsTo('App\User','client_id');
    }

    /**
     * Each Record belongs to appointment
     *
     * @return mixed
     */

    public function appointment()
    {
        return $this->belongsTo('App\AdvisorClientAppointment','id', 'match_id');
    }

}
