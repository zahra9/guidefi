<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    protected $guarded = [''];

    protected $table = 'client_subscription_plan';
    protected $fillable = [
        'user_id',
        'package_id',
        'active',
        'billing_date',
        'customer_id',
        'next_billing_date',
        'is_corporate_user'
    ];


}



