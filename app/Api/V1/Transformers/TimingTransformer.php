<?php

namespace App\Api\V1\Transformers;

use App\Timing;
use League\Fractal\TransformerAbstract;

class TimingTransformer extends TransformerAbstract
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
            'monday_start' => $timing->monday_start,
            'monday_end' => $timing->monday_end,
            'monday_status' => $timing->monday_status,
            'tuesday_start' => $timing->tuesday_start,
            'tuesday_end' => $timing->tuesday_end,
            'tuesday_status' => $timing->tuesday_status,
            'wednesday_start' => $timing->wednesday_start,
            'wednesday_end' => $timing->wednesday_end,
            'wednesday_status' => $timing->wednesday_status,
            'thursday_start' => $timing->thursday_start,
            'thursday_end' => $timing->thursday_end,
            'thursday_status' => $timing->thursday_status,
            'friday_start' => $timing->friday_start,
            'friday_end' => $timing->friday_end,
            'friday_status' => $timing->friday_status,
            'saturday_start' => $timing->saturday_start,
            'saturday_end' => $timing->saturday_end,
            'saturday_status' => $timing->saturday_status,
            'sunday_start' => $timing->sunday_start ,
            'sunday_end' => $timing->sunday_end,
            'sunday_status' => $timing->sunday_status,
        ];
    }
}
