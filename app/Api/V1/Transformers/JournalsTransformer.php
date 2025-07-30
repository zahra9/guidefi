<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Helpers\Utility;
use App\ClientJournal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;

class JournalsTransformer extends TransformerAbstract
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
    public function transform(ClientJournal $journal)
    {
        $currentTherapistId = 0;
        if(!is_null(Auth::user()->selectedTherapists()->where('is_active', 1)->first())){
            $currentTherapistId = Auth::user()->selectedTherapists()->where('is_active', 1)->first()->therapist_id;
        }
        return [
            'id' => $journal->id,
            'mood' => $journal->client_mood,
            'title' => $journal->journal_title,
            'description' => $journal->journal_description,
            'is_shared' => $currentTherapistId != 0 ? (Auth::user()->shareJournals()->where('clients_shared_feedback_and_journals_with_therapist.therapist_id', $currentTherapistId)->where('active', 1)->where('journal_id',$journal->id)->count() > 0 ? 1 : 0) : 1,
            'date' => Carbon::parse($this->gettime($journal->created_at))->format('m/d/Y h:i A'),
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
