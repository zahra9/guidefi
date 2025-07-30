<?php

namespace App\Api\V1\Transformers;

use App\Question;
use App\QuestionSection;
use App\QuestionType;
use League\Fractal\TransformerAbstract;

class QuestionTypeTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = ['section'];

    /**
     * List of resources to automatically include.
     *
     * @var array
     */
    protected $defaultIncludes = ['section'];

    /**
     * @property string name
     * @property string email
     */
    public function transform(QuestionType $question)
    {
        return [
            'id' => $question->id,
            'title' => $question->name,
        ];
    }

    public function includeSection(QuestionType $questionSection)
    {
        $questionSection = $questionSection->questionSection;

        return $this->collection($questionSection, new QuestionSectionTransformer());
    }


}
