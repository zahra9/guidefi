<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlanChange extends Model
{
    protected $guarded = [''];

    protected $table = 'client_subscription_plan_changes';
    protected $fillable = [
        'user_id',
        'current_subscription_plan_id',
        'new_subscription_plan_id',
        'activation_date',
        'is_active',
        'is_processed',
        'processed_date',
        'created_at',
        'updated_at'
    ];
}



