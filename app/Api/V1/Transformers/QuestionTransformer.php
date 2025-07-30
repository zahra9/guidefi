<?php

namespace App\Api\V1\Transformers;

use App\Question;
use League\Fractal\TransformerAbstract;

class QuestionTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
//    protected $availableIncludes = [];

    /**
     * List of resources to automatically include.
     *
     * @var array
     */
//    protected $defaultIncludes = ['answer','options'];

    /**
     * @property string name
     * @property string email
     */
    public function transform(Question $question)
    {
        return [
            'section_name' => [
                'id' => $question->id,
                'title' => $question->question,
                'orderNo' => $question->order_no,
                'type' => $question->question_type,
                'weightage' => $question->weightage,
                'validation_type' => $question->validation_type,
            ]
        ];
    }

    public function includeAnswer($question)
    {
        if(!is_null($question->answer)){
            $question = $question->answer;
            return $this->item($question, new AnswerTransformer());
        }

    }

    public function includeOptions($options)
    {
        $options = $options->options;
        return $this->collection($options, new QuestionOptionsTransformer());

    }
}
