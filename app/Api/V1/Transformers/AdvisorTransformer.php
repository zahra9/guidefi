<?php

namespace App\Api\V1\Transformers;

use App\User;
use Illuminate\Support\Facades\Log;
use League\Fractal\TransformerAbstract;

class AdvisorTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include.
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @property string name
     * @property string email
     */
    public function transform(User $user)
    {
        return  [
            'id' => $user->id,
            'name' => $user->person->first_name . ' ' . $user->person->last_name,
            "age" => $user->person->age . ' Years',
            'image_url' => $user->person->image_url,
            'gender' => $user->person->gender,
            'lang' => $user->person->lang,
            'religion' => $user->person->religion,
            'experience_level' => $user->person->experience_level,
            'email' => $user->person->email,
            'phone' => $user->person->phone,
            'address' => strtoupper($user->person->state). ', USA',
            'status' => 'offered',


            'company_name' => $user->advisorrequest->company_name,
            'finance_link' => $user->advisorrequest->finance_link, // not inserting into database.
            'company_logo' => $user->advisorrequest->company_logo,
            'licence' => $user->advisorrequest->licence ? $user->advisorrequest->licence : $user->person->academic_records,
            'compensation_type' => $user->advisorrequest->compensation_type,

            'website' => $user->person->website,
            'area_of_experties' => $user->advisorrequest->area_of_experties,
            'discovery_call_done' => 0,
            'ratings' => ratingCalc($user),//4.5,
            'bio' => $user->person->bio
        ];
    }

}
