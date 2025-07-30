<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'persons';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'phone', 'email', 'image_url', 'zipcode', 'driving_licence', 'website', 'birth_date', 'role_id', 'gender', 'age', 'lang', 'state', 'religion', 'academic_records', 'experience_level', 'bio', 'user_id','race','json_data','licence','term_condition','is_rate_card'];

    /**
     * A profile belongs to a user.
     *
     * @return mixed
     */
    public function advisorRequest()
    {
        return $this->belongsTo('App\Advisorrequest');
    }

}
