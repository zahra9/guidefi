<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Helpers\Utility;
use App\clientFeedbackFromTherapist;
use App\Note;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;

class NotesTransformer extends TransformerAbstract
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
    public function transform(Note $note)
    {
        return [
            'id' => $note->id,
            'note' => $note->note,
            'created_at' => Carbon::parse($this->gettime($note->created_at))->format('m/d/Y h:i A'),
            'updated_at' => Carbon::parse($this->gettime($note->updated_at))->format('m/d/Y h:i A'),
        ];
    }

    public function gettime($datetime){
        $utilityClass = new Utility();
        if($this->request->has('timezone') && $this->request->get('timezone') !== config('app.timezone')){
            $from = config('app.timezone');
            $to = $this->request->get('timezone');
            return $utilityClass->convertTimeZone($datetime, $from, $to);
        }
        return $datetime;
    }

}
