<?php

namespace App\Api\V1\Controllers;

use App\AdvisorClientAppointment;
use App\Api\V1\Helpers\FcmController;
use App\Api\V1\Helpers\StripeHelper;
use App\Api\V1\Transformers\FeedbackTransformer;
use App\Api\V1\Transformers\NotesTransformer;
use App\ClientAdvisorDeals;
use App\clientFeedbackFromTherapist;
use App\FeedbackDealFinish;
use App\Http\Controllers\Controller;
use App\MatchedAdvisorList;
use App\Note;
use App\Question;
use App\RatesCardOffers;
use App\Therapist;
use App\TherapistRatingReviews;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\User as AppUser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;



class FeedbackController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function clientFeedback(Request $request)
    {
        try{
            $user = Auth::user();

            $deal = ClientAdvisorDeals::find($request->deal_id);


            if(!$deal){
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No Deal found.'
                ]);
            }
            $storeFeedback = FeedbackDealFinish::create([
                'client_id' => $user->id,
                'advisor_id' => $deal->advisor_id,
                'expertise_level' => $request->get('expertise_level'),
                'availability' => $request->get('availability'),
                'communication' => $request->get('communication'),
                'feedback' => $request->get('feedback'),
                'feedback_by' => 'client'
            ]);

            if($storeFeedback){
                $matchedRecord = MatchedAdvisorList::where('id', $deal->match_id)->first();
                $matchedRecord->client_request_status = 5; // Deal ended
                $matchedRecord->advisor_request_status = 5; // Deal ended
                $matchedRecord->save();

                $offeredRecord = RatesCardOffers::where('id', $deal->offer_id)->first();
                $offeredRecord->status = 2;
                $offeredRecord->end_time = Carbon::now();
                $offeredRecord->save();

                $appointment = AdvisorClientAppointment::
                where('client_id', $user->id) ->
                   where('advisor_id', $deal->advisor_id)
                    ->where('appointment_status', 'Booked')->first();

                $appointment->appointment_status = 'Ended';
                $appointment->save();

                $deal->status = 0;
                $deal->deal_end_date = Carbon::now();
                $deal->save();

                $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                $description = $user->name . ' ' . 'has given you feedback.';
                $response = $fcmController->pushNotification('Client Feedback', $description, 'feedback', $deal->advisor_id);
                if(!is_null($deal->subscription) && isset($deal->subscription)) {
                    // cancel subscription
//                    $stripeHelper = new StripeHelper();
//                    $cancel = $stripeHelper->subscriptions->cancel()
                    $stripe = new \Stripe\StripeClient(
                        Config::get('settings.stripe_secret')
                    );
                    $stripe->subscriptions->cancel(
                        $deal->subscription,
                        []
                    );
                }
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Thank you for your feedback.'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No Deal Found'
                ]);
            }

        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function advisorFeedback(Request $request)
    {
        try{
            $user = Auth::user();

            $deal = ClientAdvisorDeals::find($request->deal_id);

            if(!$deal){
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No Deal found.'
                ]);
            }
            $storeFeedback = FeedbackDealFinish::create([
                'client_id' => $deal->client_id,
                'advisor_id' => $user->id,
                'expertise_level' => $request->get('expertise_level'),
                'availability' => $request->get('availability'),
                'communication' => $request->get('communication'),
                'feedback' => $request->get('feedback'),
                'feedback_by' => 'advisor'
            ]);

            if($storeFeedback){
                $matchedRecord = MatchedAdvisorList::where('id', $deal->match_id)->first();
                $offeredRecord = RatesCardOffers::where('id', $deal->offer_id)->first();

                $matchedRecord->client_request_status = 5; // Deal ended
                $matchedRecord->advisor_request_status = 5; // Deal ended
                $matchedRecord->save();

                $offeredRecord->status = 2;
                $offeredRecord->end_time = Carbon::now();
                $offeredRecord->save();


                $deal->status = 0;
                $deal->deal_end_date = Carbon::now();
                $deal->save();


                $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                $description = $user->name . ' ' . 'has given you feedback.';
                $response = $fcmController->pushNotification('Advisor Feedback', $description, 'feedback', $deal->advisor_id);


                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Thank you for your feedback.'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No Deal Found'
                ]);
            }

        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function getFeedback(Request $request)
    {
        try{
            $user = Auth::user();

            $stripeHelper = new StripeHelper();
            $invoices = $stripeHelper->refreshStripeLogTable();

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            if($user->isAdvisor()){
                $getFeedback = FeedbackDealFinish::where('advisor_id',$user->id)->orderBy('created_at', 'DESC')->get();
            }else{
                $getFeedback = FeedbackDealFinish::where('client_id',$user->id)->orderBy('created_at', 'DESC')->get();
            }
            $getFeedback = new Collection($getFeedback, new FeedbackTransformer($request));

            $manager = $manager->createData($getFeedback);

            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Successfully retrieved!'
            ]);
        }catch (ErrorException $exception){
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return Response
     */
    public function show(Question $question)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @return Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Question $question
     * @return Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
