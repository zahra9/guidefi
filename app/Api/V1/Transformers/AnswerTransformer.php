<?php

namespace App\Api\V1\Transformers;

use App\Answers;
use Auth;
use League\Fractal\TransformerAbstract;

class AnswerTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = ['options'];

    /**
     * List of resources to automatically include.
     *
     * @var array
     */
    protected $defaultIncludes = ['options'];

    /**
     * @property string name
     * @property string email
     */
    public function transform(Answers $answer)
    {
        return [
            'id' => $answer->id,
            'inputAnsText' => $answer->answer_text,
        ];
    }

    public function includeOptions($answerOption)
    {
        if(Auth::check()){
            return $this->collection($answerOption->options, new QuestionOptionsObjectTransformer());
        }else{
            return [];
        }

    }
}
