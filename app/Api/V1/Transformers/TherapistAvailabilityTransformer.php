<?php

namespace App\Api\V1\Transformers;

use App\Timing;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TherapistAvailabilityTransformer extends TransformerAbstract
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
    public function transform($timing)
    {
        return [
            'id' => $timing->id,
            'therapist_id' => $timing->therapist_id,
            'day' => $timing->day,
            'start_time' => $timing->start_time,
            'end_time' => $timing->end_time,
            'time_zone' => $timing->time_zone,
            'created_at' => Carbon::parse($timing->created_at)->format('m/d/Y h:i A'),
            'updated_at' => Carbon::parse($timing->created_at)->format('m/d/Y h:i A')
        ];
    }
}
