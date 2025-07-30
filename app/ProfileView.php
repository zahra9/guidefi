<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileView extends Model
{
    protected $table = 'profile_views';
    protected $fillable = ['client_id','advisor_id','client_ip'];
}
