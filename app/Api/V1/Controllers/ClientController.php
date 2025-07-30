<?php

namespace App\Api\V1\Controllers;

use App\AdvisorClientAppointment;
use App\Answers;
use App\Api\V1\Helpers\StripeHelper;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\QuestionTransformer;
use App\Api\V1\Transformers\QuestionTypeTransformer;
use App\BpAnswers;
use App\BpOptions;
use App\BpQuestion;
use App\ClientAdvisorDeals;
use App\Clientrequest;
use App\Http\Controllers\Controller;
use App\MatchedAdvisorList;
use App\Options;
use App\Question;
use App\QuestionType;
use App\RatesCardOffers;
use App\Repositories\EmailManager;
use App\StripePaymentLog;
use App\User;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;


class ClientController extends Controller
{


    /*
     * This is a request for the client to pay for an advisor on monthly basis.
     *  So most probably this would be a subscription not a one time charge.
     * */
    public function myAdvisors()
    {
        try {

            $user = Auth::user(); // client
            $data = [];
            $contractDetails = ClientAdvisorDeals::with('offer')->with('appointment')->with('match')->with('advisor')->where('client_id', $user->id)->get();

            if ($contractDetails) {
                foreach ($contractDetails as $record) {
                    if ($record->advisor->person) {

                        // If an offer is sent to the other user then find and attach with the request.
                        $callLog = null;
                        if ($record->appointment && $record->appointment->id) {
                            $callLog = $user->advisorCallLogs()->where('advisor_id', $record->client_id)->where('appointment_id', $record->appointment->id)->first();
                        }

                        $offer_status = 'match';
                        if($record->match->discovery_call) $offer_status = 'contract_offer';
                        if (Carbon::parse($record->match->advisor_request_date)->addDays(2)->lt(Carbon::now())) $offer_status = 'expired';

                        $contract = null;
                        $contract_status = 'contract_active';
                        if ($record->offer) {

                            $contract_status = $record->status ? 'contract_active' : 'contract_ended';

                            if ($record->offer->ratecard) {

                                $answers = $record->offer->ratecard->answers;
                                $servicesArray = $answers->filter(function ($query) {
                                    return $query->question_text == 'Please indicate the financial product/services that your firm provides:';
                                });

                                $getServices = implode(', ', $servicesArray->pluck('answer_text')->toArray());

                                $contract = [
                                    "id" => $record->id,
                                    "offer_id" => $record->offer->id,
                                    "appointment_id" => $record->appointment_advisor_client_id,
                                    "topic" => $getServices,
                                    "start_date" => $record->deal_start_date,
                                    "end_date" => $contract_status == 'contract_ended' ?  $record->deal_end_date : null,
                                    "contract_renew_time" => $record->offer->end_time,
                                    "status" => $record->offer->status,
                                    "offer_title" => $getServices,
                                    "price" => '$'.number_format($record->offer->amount, 2),
                                    "price_tag" => $record->offer->price_tag
                                ];
                            }
                        }

                        // Advisor information
                        $data[] = [
                            "id" => $record->advisor_id, //$client->person->id,
                            "name" => $record->advisor->person->first_name . ' ' . $record->advisor->person->last_name,
                            "image_url" => $record->advisor->person->image_url,
                            "contract_status" => $contract_status,
                            'offer_status' => $offer_status, // match, contract_offer (After discovery call) , contract_offer_rejected
                            'contract' => $contract,
                        ];
                    }
                }
                return response()->json([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Advisors successfully retrieved!'
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => "",
                    'message' => 'No data found!'
                ]);
            }

        } catch (ErrorException $exception) {
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }


    }

    public function acceptOffer(Request $request)
    {
//        try {
            $user = Auth::user(); // Client

            if ($request->get('payment_for') == 'advisor') {
                $client_id = $user->id;
                $advisor_id = $request->get('user_id');
            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Only clients can accept and reject offers from advisor'
                ]);
            }

            $offeredRecord = RatesCardOffers::where('id', $request->offer_id)->first();

            $matchedRecord = MatchedAdvisorList::where('advisor_id', $advisor_id)->where('client_id', $client_id)->where('client_request_status', 2)->where('advisor_request_status', 1)->first();
            if (!$matchedRecord) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No matching found between you two.'
                ]);
            }

            if (!$offeredRecord) {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'No Offer found.'
                ]);
            }

            $stripeHelper = new StripeHelper();
            $card_id = $request->get('card_id');
            $currency = 'USD';

            if(is_null($user->customer_id) || $user->customer_id == ''){
                $customer = $stripeHelper->addCustomer($user,$advisor_id);
            }else{
                $customer = $stripeHelper->getCustomer($user,$advisor_id);
            }

            if(!$request->has('card_id') || $card_id == ''){
                $card = $stripeHelper->addCard($customer->id, $request);
            }else{
                $card = $stripeHelper->getCard($customer->id, $card_id);
            }


            if(!$customer->default_source || $request->get('is_default') == 1){
                $customer->default_source = $card->id;
                $customer->save();
            }

            if($offeredRecord->price_tag == 'monthly'){
                $price =  $request->amount*100;
                $plan = 'payment for advisor on montly subscription. @'. $price;


                $metadata = ['contract_service'=> $request->descreption, "advisor_id" => $matchedRecord->advisor->id, 'advisor_name'=>$matchedRecord->advisor->name, 'advisor_email'=> $matchedRecord->advisor->email];
                $interval = 'month';

                $stripePlan = $stripeHelper->createPlan($plan, $price, $interval, $metadata, $currency);

                if($stripePlan){
                    $metadata = ['contract_service'=> $request->descreption, "advisor_id" => $matchedRecord->advisor->id, 'advisor_name'=>$matchedRecord->advisor->name, 'advisor_email'=> $matchedRecord->advisor->email];
                    $subscription = $stripeHelper->createSubscription($customer, $stripePlan, $request->description, $metadata, $currency);
                }

            }else{

                $charge = $stripeHelper->createCharge($customer, $request, $currency);
            }

            if ($subscription || $charge) {
                if ($matchedRecord) {

                    $appointmentAdvisorClient = AdvisorClientAppointment::where('advisor_id', $advisor_id)->where('client_id', $client_id)->where('appointment_status', 'Booked')->where('is_discovery_call', 1)->first();


                    $planId = null;
                    $subscriptionId = null;
                    $chargeId = null;

                    if($offeredRecord->price_tag == 'monthly'){
                        $planId = $stripePlan->id;
                        $subscriptionId = $subscription->id;
                    }else{
                        $chargeId = $charge->id;
                    }
                    ClientAdvisorDeals::create(
                        [
                            'description' => $request->descreption,
                            'client_id' => $client_id,
                            'advisor_id' => $advisor_id,
                            'offer_id' => $offeredRecord->id,
                            'match_id' => $matchedRecord->id,
                            'stripe_plan_id' => $planId,
                            'stripe_subcs_id' => $subscriptionId,
                            'stripe_charge_id' => $chargeId,
                            'deal_amout' => $request->amount * 100,
                            'commission_percentage' => 1,
                            'commission_amount' => 1,
                            'deal_start_date' => Carbon::now(),
                            'deal_end_date' => Carbon::now()->addMonth(),
                            'status' => 1,
                            'subscription'=>$subscriptionId,
                            'appointment_advisor_client_id' => $appointmentAdvisorClient->id,
                        ]);

                    $matchedRecord->client_request_status = 4; // payment done for this client
                    $matchedRecord->advisor_request_status = 4; // payment done for this client
                    $matchedRecord->save();


                    $offeredRecord->status = 1;
                    $offeredRecord->start_time =  Carbon::now();
                    $offeredRecord->end_time = Carbon::now()->addMonth();
                    $offeredRecord->save();



                    $stripeHelper->createStripeLog($user, $request, $card, $chargeId, $currency, $subscriptionId, $subscription);

                    //email trigger for reminding client after successful payment for advisor service

                    EmailManager::sendMailQueue('PAYMENT SUCCESSFULLY Done FROM ADVISOR','Advisor Payment Service successfully done', $user->name, $user->email);

                }
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Offer accepted successfully.'
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Payment failed, Try Again later'
                ]);
            }

      /*  } catch (ErrorException $e) {

            return response()->json([
                'status' => false,
                'data' => $e,
                'message' => $e->getMessage()
            ]);
        }*/
    }


    public function renewRequest(Request $requests)
    {
        try {
            $user = Auth::user();
            $deal_id = $requests->deal_id;
            $prevDeal = ClientAdvisorDeals::where('id', $deal_id)->first();

            if (!$prevDeal) {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Contract not found'
                ]);
            }
            if ($prevDeal->status) {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'You already has an active deal with this advisor.'
                ]);
            }

            if ($requests->type == 'renew-offer') {


                if (!$prevDeal->status) {
                    return response()->json([
                        'status' => true,
                        'data' => $prevDeal,
                        'message' => 'New contract request is initiated'
                    ]);
                }
            } elseif ($requests->type == 'request-offer') {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'We are not processing these request right now, Will be available later.'
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'No request type found.'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something wents wrong, please try later'
            ]);
        }

    }


    public function renewOffer(Request $request)
    {
        try {
            $user = Auth::user();

            $prevDeal = ClientAdvisorDeals::with('match')->with('offer')->where('id', $request->deal_id)->first();

            if (!$prevDeal) {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Contract not found'
                ]);
            }
                if ($prevDeal->status) {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'You already has an active deal with this advisor.'
                ]);
            }

            $matchedRecord = $prevDeal->match;
            $offeredRecord = $prevDeal->offer;

            $advisor_id = $prevDeal->advisor_id;
            $advisor = User::where('id', $advisor_id)->first();
            $stripeHelper = new StripeHelper();
            $card_id = $request->get('card_id');
            $currency = 'USD';

            if(is_null($user->customer_id) || $user->customer_id == ''){
                $customer = $stripeHelper->addCustomer($user);
            }else{
                $customer = $stripeHelper->getCustomer($user);
            }

            if(is_string($customer)){
                $customer = $stripeHelper->addCustomer($user);
            }

            if(!$request->has('card_id') || $card_id == ''){
                $card = $stripeHelper->addCard($customer->id, $request);
            }else{
                $card = $stripeHelper->getCard($customer->id, $card_id);
            }

            if(is_string($card)){
                $card = $stripeHelper->addCard($customer->id, $request);
            }


            if(!$customer->default_source || !$request->has('card_id')){
                $customer->default_source = $card->id;
                $customer->save();
            }

            if($offeredRecord->price_tag == 'monthly'){
                $price =  $prevDeal->deal_amout;
                $plan = 'payment for advisor on montly subscription. @'. $price;


                $metadata = ['contract_service'=> $prevDeal->descreption, "advisor_id" => $advisor_id, 'advisor_name'=>$advisor->name, 'advisor_email'=> $advisor->email];
                $interval = 'month';

                $stripePlan = $stripeHelper->createPlan($plan, $price, $interval, $metadata, $currency);
                if($stripePlan){
                    $metadata = ['contract_service'=> $prevDeal->descreption, "advisor_id" => $advisor->id, 'advisor_name'=>$advisor->name, 'advisor_email'=> $advisor->email];
                    $subscription = $stripeHelper->createSubscription($customer, $stripePlan, $prevDeal->descreption, $metadata, $currency);
                }

            }else{

                $charge = $stripeHelper->createCharge($customer, $request, $currency);
            }

            if ($subscription || $charge) {
                if ($matchedRecord) {

                    $newAppointment = AdvisorClientAppointment::create(
                        [
                            'client_id' => $user->id,
                            'advisor_id' => $advisor_id,
                            'advisor_timezone' => $prevDeal->advisor_timezone,
                            'client_timezone' => $prevDeal->client_timezone,
                            'appointment_date' => Carbon::now(),
                            'start_time' => Carbon::now()->format('H:i'),
                            'end_time' => Carbon::now()->addMinutes(30)->format('H:i'),
                            'appointment_day' => Carbon::now()->format('l'),
                            'appointment_status' => 'Booked',
                        ]);
                    $newMatched = MatchedAdvisorList::create(
                        [
                            'client_id' => $user->id,
                            'advisor_id' => $advisor_id,
                            'client_request_status' => 4,
                            'advisor_request_status' => 4,

                            'client_request_date' => Carbon::now(),
                            'advisor_request_date' => null
                        ]);

                    $offeredRecord = RatesCardOffers::where('id', $prevDeal->offer_id)->first();
                    $offeredRecord->status = 1;
                    $offeredRecord->end_time = Carbon::now();
                    $offeredRecord->save();


                    $newOffered = $offeredRecord->replicate();
                    $newOffered->status = 1;
                    $newOffered->start_time =  Carbon::now();
                    $newOffered->end_time = Carbon::now()->addMonth();
                    $newOffered->save();

                    $prevDeal->contract_renew_ref = $prevDeal->id;
                    $prevDeal->save();

                    $planId = null;
                    $subscriptionId = null;
                    $chargeId = null;

                    if($offeredRecord->price_tag == 'monthly'){
                        $planId = $stripePlan->id;
                        $subscriptionId = $subscription->id;
                    }else{
                        $chargeId = $charge->id;
                    }

                    $newDeal = $prevDeal->replicate();
                    $newDeal->offer_id = $newOffered->id;
                    $newDeal->match_id = $newMatched->id;
                    $newDeal->stripe_plan_id = $planId;
                    $newDeal->stripe_subcs_id = $subscriptionId;
                    $newDeal->stripe_charge_id = $chargeId;

                    $newDeal->deal_start_date =  Carbon::now();
                    $newDeal->deal_end_date = Carbon::now()->addMonth();

                    $newDeal->appointment_advisor_client_id = $newAppointment->id;
                    $newDeal->save();

                    $reqData = [
                        'amount' =>$prevDeal->deal_amout,
                        'user_id' =>$prevDeal->advisor_id,
                        'descreption' =>$prevDeal->descreption
                    ];

                    $stripeHelper->createStripeLog($user, null, $card, $chargeId, $currency, $subscriptionId, $subscription, $metadata, $newDeal);

                    EmailManager::sendMailQueue('PAYMENT SUCCESSFULLY Done FROM ADVISOR','Advisor Payment Service successfully done', $user->name, $user->email);

                }
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Offer renewed successfully.'
                ]);
            }

        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'status' => false,
                'message' => 'Something wents wrong, please try later'
            ]);
        }

    }
}
