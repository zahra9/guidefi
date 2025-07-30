<?php

namespace App\Api\V1\Transformers;

use App\Faq;
use League\Fractal\TransformerAbstract;

class FaqTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */

    /**
     * List of resources to automatically include.
     *
     * @var array
     */

    /**
     * @property string name
     * @property string email
     */
    public function transform(Faq $faq)
    {
        return [
            'id' => $faq->id,
            'question' => $faq->question,
            'answer' => $faq->answer,
        ];
    }
}
