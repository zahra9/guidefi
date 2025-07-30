<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTypeRanges extends Model
{
    use SoftDeletes;
    protected $table = 'user_type_ranges';
    protected $guarded = ['id'];

    protected $fillable = [
        'x_factor', 'user_type', 'low_range', 'high_range', 'lead_price',
    ];
}
