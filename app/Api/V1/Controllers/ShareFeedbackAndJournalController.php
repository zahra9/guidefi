<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\FeedbackTransformer;
use App\Api\V1\Transformers\JournalsTransformer;
use App\clientFeedbackFromTherapist;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Support\Facades\DB;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use App\Http\Controllers\Controller;
use League\Fractal\Resource\Collection;

class ShareFeedbackAndJournalController extends Controller
{
    public function shareFeedback(Request $request){
        $this->validate($request, [
            'feedback_id' =>'sometimes|required|exists:client_feedback_from_therapist,id'
        ]);

        try{
            $user = Auth::user();

            $selectedTherapist = $user->selectedTherapists()->where('is_active',1)->first();

            if(is_null($selectedTherapist) || is_null($selectedTherapist->therapist)){
                return response()->json([
                    'status' => false,
                    'message' => 'No, default selected therapist found!',
                    'data' => null,
                ]);
            }
            $getFeedbaks = clientFeedbackFromTherapist::where('user_id', $user->id)->where('therapist_id', '!=', $selectedTherapist->therapist_id)->pluck('id', 'id')->toArray();

            $user->shareFeedback()->attach($getFeedbaks, [
                  'therapist_id' => $selectedTherapist->therapist->id,
                  'type' => 'feedback',
                  'active' => 1
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Feedback has been shared with '. $selectedTherapist->therapist->user->name,
                'data' => null,
            ]);

        }catch (ErrorException $exception){
            dd($exception);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
                'data' => null,
            ]);
        }
    }

    public function getSharedFeedback(Request $request){
        $this->validate($request, [
            'client_id' =>'required|exists:users,id'
        ]);

        try{
            $user = Auth::user();
            $therapist = $user->therapist;

            if(is_null($therapist)){
                return response()->json([
                    'status' => false,
                    'message' => 'Therapist not found!',
                    'data' => null,
                ]);
            }

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $clientId = $request->get('client_id');
            $feedback = $therapist->shareFeedback()->where('client_id',$clientId)->where('type','feedback')->where('active',1)->get();

            $feedbacks = [];
            foreach ($feedback as $key2 => $val2){
                $feedbacks[] = $val2->id;
            }


            $getFeedbaks = clientFeedbackFromTherapist::where('user_id', $clientId)->whereNotIn('id', $feedbacks)->where('therapist_id',  $therapist->id)->get();
            foreach ($feedback as $key => $val){
                $getFeedbaks[] = $val;
            }
            $getFeedbaks = $getFeedbaks->sortByDesc('created_at');

            $feedback = new Collection($getFeedbaks, new FeedbackTransformer($request));

            $manager = $manager->createData($feedback);

            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Successfully retrieved!'
            ]);

        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
                'data' => null,
            ]);
        }
    }

    // Sharing Journal API
    public function shareJournals(Request $request){
        /*$this->validate($request, [
            'journal_id' =>'required|exists:client_journals,id'
        ]);*/

        try{
            $user = Auth::user();
            $selectedTherapist = $user->selectedTherapists()->where('is_active',1)->first();

            if(is_null($selectedTherapist) || is_null($selectedTherapist->therapist)){
                return response()->json([
                    'status' => false,
                    'message' => 'No, default selected therapist found!',
                    'data' => null,
                ]);
            }
            if(is_array(explode(',', $request->get('journal_id')))){
                $user->shareJournals()->attach(explode(',', $request->get('journal_id')),[
                    'therapist_id' => $selectedTherapist->therapist->id,
                    'type' => 'journal',
                    'active' => 1
                ]);
            }else{
                $user->shareJournals()->attach($request->get('journal_id'),[
                    'therapist_id' => $selectedTherapist->therapist->id,
                    'type' => 'journal',
                    'active' => 1
                ]);
            }


            return response()->json([
                'status' => true,
                'message' => 'Journal successfully shared with '. $selectedTherapist->therapist->user->name,
                'data' => null,
            ]);

        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
                'data' => null,
            ]);
        }
    }

    // UnSharing Journal API
    public function unshare(Request $request){
        try{


            $this->validate($request, [
                'type' =>'required|string',
//                'journal_id' => 'sometimes|required|exists:client_journals,id'
//                'journal_id' => 'exclude_if:type,feedback|required|exists:client_journals,id'
            ]);

            $user = Auth::user();
            $selectedTherapist = $user->selectedTherapists()->where('is_active',1)->first();

            if(is_null($selectedTherapist) || is_null($selectedTherapist->therapist)){
                return response()->json([
                    'status' => false,
                    'message' => 'No, default selected therapist found!',
                    'data' => null,
                ]);
            }

            if($request->has('journal_id') && $request->get('journal_id') != 0){
                if(is_array(explode(',', $request->get('journal_id')))){
                    $user->shareJournals()->whereIn('journal_id', explode(',', $request->get('journal_id')))->update(['active' => 0 ]);
                }else{
                    $user->shareJournals()->where('journal_id', $request->get('journal_id'))->update(['active' => 0 ]);
                }
            }else{
                $user->shareFeedback()->update(['active' => 0 ]);
            }
//            $user->shareJournals()->sync([]);

            return response()->json([
                'status' => true,
                'message' => ($request->has('journal_id') ? 'Journal ' : 'Feedback ') . 'has been unshared with '. $selectedTherapist->therapist->user->name,
                'data' => null,
            ]);

        }catch (ErrorException $exception){
            dd($exception);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
                'data' => $exception
            ]);
        }
    }

    public function getSharedJournals(Request $request){
        $this->validate($request, [
            'client_id' =>'required|exists:users,id'
        ]);

        try{
            $user = Auth::user();
            $therapist = $user->therapist;

            if(is_null($therapist)){
                return response()->json([
                    'status' => false,
                    'message' => 'Therapist not found!',
                    'data' => null,
                ]);
            }

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $clientId = $request->get('client_id');
            $journal = $therapist->shareJournals()->where('clients_shared_feedback_and_journals_with_therapist.client_id',$clientId)->where('type','=','journal')->where('active',1)->get();
            $journal = new Collection($journal, new JournalsTransformer($request));

            $manager = $manager->createData($journal);

            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Successfully retrieved!'
            ]);

        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
                'data' => null,
            ]);
        }
    }

}