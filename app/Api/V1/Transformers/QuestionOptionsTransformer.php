<?php

namespace App\Api\V1\Transformers;

use App\Options;
use League\Fractal\TransformerAbstract;

class QuestionOptionsTransformer extends TransformerAbstract
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
    public function transform(Options $option)
    {
        return [
            'id' => $option->id,
            'title' => $option->label,
        ];
    }

}
