<?php

namespace App\Api\V1\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
        return [
            'id' => $user->id,
            "name" => $user->person->first_name . ' ' . $user->person->last_name,
            "gender" => $user->person->gender,
            "age" => $user->person->age . ' Years',
            'status' => 'offered',
            "income_level" => $user->clientRequest->income_level !== null ? $user->clientRequest->income_level : "",
            "investment_level" => $user->clientRequest->investment_level !== null ? $user->clientRequest->investment_level : "",
            "assets" => $user->clientRequest->assets !== null ? $user->clientRequest->assets : "",
            'address' => strtoupper($user->person->state). ', USA',
            'image_url' => $user->person->image_url,
            'contact_number' => $user->person->phone,
            'contact_email' => $user->email,
        ];
    }

}
