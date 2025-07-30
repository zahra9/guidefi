<?php

namespace App\Api\V1\Transformers;

use App\ClientSelectedTherapist;
use League\Fractal\TransformerAbstract;

class SelectedTherapistTransformer extends TransformerAbstract
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
    protected $defaultIncludes = ['therapist'];

    /**
     * @property string name
     * @property string email
     */
    public function transform(ClientSelectedTherapist $client)
    {
        return [
            'id' => !is_null($client->therapist) ? $client->therapist->id: '',
            'name' => !is_null($client->therapist) && !is_null($client->therapist->user) ? $client->therapist->user->name : '',
            'email' => !is_null($client->therapist) && !is_null($client->therapist->user) ? $client->therapist->user->email : '',
            'rating' => !is_null($client->therapist) ? $client->therapist->rating: '',
            'active' => $client->is_active
        ];
    }

    public function includeTherapist($selectedTherapist)
    {
        $therapist = $selectedTherapist->therapist;
        return $this->item($therapist, new TherapistTransformer());
    }



}
