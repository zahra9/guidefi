<?php

namespace App\Api\V1\Transformers;

use App\Options;
use League\Fractal\TransformerAbstract;

class QuestionOptionsObjectTransformer extends TransformerAbstract
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
    public function transform($option)
    {
        return [
            'id' => !is_null($option->option) ? $option->option->id : '',
            'title' => !is_null($option->option) ? $option->option->label : ''
        ];
    }

}
