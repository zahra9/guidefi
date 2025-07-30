<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Helpers\Utility;
use App\clientFeedbackFromTherapist;
use App\FeedbackDealFinish;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;

class FeedbackTransformer extends TransformerAbstract
{
    public function __construct($request)
    {
        $this->request = $request;
    }
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
    public function transform(FeedbackDealFinish $feedback)
    {
        return [
            'id' => $feedback->id,
            'advisor_id' => $feedback->advisor_id,
            'client_id' => $feedback->client_id,
            'feedback' => $feedback->feedback,
            'expertise_level' => $feedback->expertise_level,
            'availability' => $feedback->availability,
            'communication' => $feedback->communication,
            'created_at' => Carbon::parse($feedback->created_at)->format('m/d/Y h:i A'),
            'updated_at' => Carbon::parse($feedback->updated_at)->format('m/d/Y h:i A')
        ];
    }

}
