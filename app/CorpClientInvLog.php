<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorpClientInvLog extends Model
{
    //
    protected $table = 'corp_client_inv_log';

    use SoftDeletes; //add this line

}
