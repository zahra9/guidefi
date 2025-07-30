<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTypes extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'client_types';

    /**
     * Mass Assignable fields of model
     * @var array
     */
    protected $fillable = [
        'client_type','user_id'
    ];

}
