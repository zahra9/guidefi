<?php

namespace App\Api\V1\Transformers;

use App\QuestionSection;
use App\QuestionType;
use League\Fractal\TransformerAbstract;

class QuestionSectionTransformer extends TransformerAbstract
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
    protected $defaultIncludes = ['questions'];

    /**
     * @property string name
     * @property string email
     */
    public function transform(QuestionSection $question)
    {
        return [
            'id' => $question->id,
            'title' => $question->label,
        ];
    }

    public function includeQuestions($question)
    {
        $question = $question->questions;

        return $this->collection($question, new QuestionTransformer());
    }
}
