<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Helpers\StripeHelper;
use App\Api\V1\Transformers\AdvisorTransformer;
use App\Api\V1\Transformers\UserTransformer;
use App\ClientAdvisorDeals;
use App\FeedbackDealFinish;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StripePaymentLogController;
use App\ProfileView;
use App\Repositories\EmailManager;
use App\StripePaymentLog;

use App\Therapist;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use League\Fractal\Resource\Collection;
use App\MatchedAdvisorList;
use App\AppointmentAadvisorClient;
use App\RateCard;
use App\Question;
use App\Answers;
use App\Options;
use App\RatesCardOffers;
use App\Api\V1\Helpers\FcmController;
use App\Api\V1\Helpers\Utility;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Appointment;
use App\ClientSelectedTherapist;
use App\SubscriptionPlan;
use App\AdvisorAvailablity;
use App\AppointmentTiming;
use App\AdvisorClientAppointment;
use App\Advisorrequest;
use App\TimeSlots;
use App\TimeSlotsFormat;
use App\Timing;
use App\User;
use App\Person;
use Carbon\Carbon;
use League\Fractal\Resource\Item;
use mysql_xdevapi\Exception;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Fractal\Manager;

class AdvisorController extends Controller
{

    public function connectedAdvisors()
    {

        try {
            $user = Auth::user();

        $match  = DB::select("CALL getAdvisorList($user->id)");

            $pluckId = collect($match)->pluck('id');

        $matchedRecord = MatchedAdvisorList::where('client_request_status', 2)->where('advisor_request_status', 1)
            ->whereIn('advisor_id', $pluckId)->where('client_id', $user->id)->with('appointment')->whereHas('advisor', function ($q) {
            }) ->pluck('advisor_id');

        $result = collect($match)->whereNotIn('id', $matchedRecord);
         $connectedAdvisors = count($result) > 0 ? collect($result) : false;

            if( count($result) < 1){

                if(!is_null($user->clientRequest)){
                    $user->clientRequest->update(['advisor_exist'=>1]);
                }

            }

            $data = [];
            if ($connectedAdvisors) {
                foreach ($connectedAdvisors as $record) {

                    $match_status = 'active';
                    $alreadyMatched = MatchedAdvisorList::where('advisor_id', $record->id)->where('client_id', $user->id)->first();
                    if (!$alreadyMatched) {
                        MatchedAdvisorList::create(['client_id' => $user->id, 'advisor_id' => $record->id, 'client_request_status' => 0, 'advisor_request_status' => 0, 'client_request_date' => Carbon::now(), 'advisor_request_date' => null]);
                    } else if ($alreadyMatched->client_request_status == 0) {
                        if (Carbon::parse($alreadyMatched->client_request_date)->addDays(2)->lt(Carbon::now())) $match_status = 'expired';
                    } else if ($alreadyMatched->client_request_status == 1) {
                        $match_status = 'requested';
                    } else if ($alreadyMatched->client_request_status == 3) {
                        $match_status = 'decline';
                    }
                    $advisor = User::where('id', $record->id)->first();
                    if ($advisor) {
                        $person = Person::where('id', $advisor->person->id)->first();
                        $personAdvisor = Advisorrequest::where('user_id',$person->user_id)->get();

                        $rate = RateCard::where('advisor_id',$person->user_id)->get();

                        //***********price range from rate card

                        $rateCards = RateCard::with('answers')->where('advisor_id', $person->user_id)->get();

                        $titleArray = ['Single W-2 Earners with Children.', 'Self-Employed Business Owners'];
                        $ratecardsRec = [];
                        foreach ($rateCards as $key => $ratecard) {
                            foreach ($titleArray as $questionkey => $question) {
                                $getRateCardAnswers = $ratecard->answers->first(function ($query) use ($question, $questionkey) {
                                    return strpos($query->question_text, $question);
                                });

                                if ($getRateCardAnswers) {
                                    if ($questionkey == 3) $price_tag = 'Fixed';
                                    if (!is_null($getRateCardAnswers->answer_text) && $getRateCardAnswers->answer_text != ''){
                                        $ratecardsRec[] = [
                                            'price' => floatval(preg_replace('/[^\d.]/', '', number_format($getRateCardAnswers->answer_text, 2))),
                                        ];
                                    }
                                }
                            }

                        }
                        //***********end
                        if ($person) {
                            $data[] = [
                                'client_id' => intval($user->id),
                                'advisor_id' => intval($record->id),
                                'first_name' => utf8_encode($person->first_name),
                                'last_name' => utf8_encode($person->last_name),
                                'phone' => utf8_encode($person->phone),
                                'email' => utf8_encode($person->email),
                                'birth_date' => utf8_encode($person->birth_date),
                                'bio' => utf8_encode($person->bio),
                                'experience_level' => utf8_encode($person->experience_level),
                                'academic_records' => utf8_encode($person->academic_records),
                                'religion' => utf8_encode($person->religion),
                                'state' => utf8_encode($person->state),
                                'lang' => utf8_encode($person->lang),
                                'age' => utf8_encode($person->age),
                                'gender' => utf8_encode($person->gender),
                                'match_status' => $match_status, // active/expired/requested/decline
                                'imageUrl' => isset($person->image_url) ? $person->image_url : null,
                                'race' => utf8_encode($person->race),
                                'expertise_level' => !is_null($person->advisorRequest)?$person->advisorRequest->area_of_experties:null,
                                'range_ratecard' =>
                                (!is_null($ratecardsRec) && collect($ratecardsRec)->count() === 1 ? Config::get('constants.CURRENCY_SIGN').
                                    collect($ratecardsRec)->min('price'):
                                    ((!is_null($ratecardsRec) && collect($ratecardsRec)->count() > 1) ? Config::get('constants.CURRENCY_SIGN')
                                        .collect($ratecardsRec)->min('price').'-'. Config::get('constants.CURRENCY_SIGN').
                                        collect($ratecardsRec)->max('price') : null)),

                            ];
                        }
                    }

                }

                return response()->json([
                    'status' => true,
                    'data' => (!is_null($data)) ? $data : [],
                    'message' => 'Advisor successfully retrieved!'
                ]);

            } else {
                //email trigger If client data is not purchased, notification that purchase of client data has expired


                return response()->json([
                    'status' => true,
                    'data' => [],
                    'message' => 'No Advisor Found!'
                ]);
            }
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }


    }


    // - [ ] Get Connected Advisors
//    public function connectedAdvisors()
//    {
//
//        try {
//            $user = Auth::user();
//            $result = DB::select("CALL getAdvisorList($user->id)");
//
////            $result = DB::select("SELECT users.id as id, name FROM users INNER JOIN role_user ON users.id = role_user.user_id WHERE role_user.role_id = 2;");
//            $connectedAdvisors = count($result) > 0 ? collect($result) : false;
//            $data = [];
//            if ($connectedAdvisors) {
//                foreach ($connectedAdvisors as $record) {
//
//                    $match_status = 'active';
//                    $alreadyMatched = MatchedAdvisorList::where('advisor_id', $record->id)->where('client_id', $user->id)->first();
//                    if (!$alreadyMatched) {
//                        MatchedAdvisorList::create(['client_id' => $user->id, 'advisor_id' => $record->id, 'client_request_status' => 0, 'advisor_request_status' => 0, 'client_request_date' => Carbon::now(), 'advisor_request_date' => null]);
//                    } else if ($alreadyMatched->client_request_status == 0) {
//                        if (Carbon::parse($alreadyMatched->client_request_date)->addDays(2)->lt(Carbon::now())) $match_status = 'expired';
//                    } else if ($alreadyMatched->client_request_status == 1) {
//                        $match_status = 'requested';
//                    } else if ($alreadyMatched->client_request_status == 3) {
//                        $match_status = 'decline';
//                    }
//                    $advisor = User::where('id', $record->id)->first();
//                    if ($advisor) {
//                        $person = Person::where('id', $advisor->person->id)->first();
//                        if ($person) {
//                            $data[] = [
//                                'client_id' => intval($user->id),
//                                'advisor_id' => intval($record->id),
//                                'first_name' => utf8_encode($person->first_name),
//                                'last_name' => utf8_encode($person->last_name),
//                                'phone' => utf8_encode($person->phone),
//                                'email' => utf8_encode($person->email),
//                                'birth_date' => utf8_encode($person->birth_date),
//                                'bio' => utf8_encode($person->bio),
//                                'experience_level' => utf8_encode($person->experience_level),
//                                'academic_records' => utf8_encode($person->academic_records),
//                                'religion' => utf8_encode($person->religion),
//                                'state' => utf8_encode($person->state),
//                                'lang' => utf8_encode($person->lang),
//                                'age' => utf8_encode($person->age),
//                                'gender' => utf8_encode($person->gender),
//                                'match_status' => $match_status, // active/expired/requested/decline
//
//                            ];
//                        }
//                    }
//                }
//
//                return response()->json([
//                    'status' => true,
//                    'data' => (!is_null($data))?$data:[],
//                    'message' => 'Advisor successfully retrieved!'
//                ]);
//
//            } else {
//                //email trigger If client data is not purchased, notification that purchase of client data has expired
//
//
//                return response()->json([
//                    'status' => true,
//                    'data' => [],
//                    'message' => 'No Advisor Found!'
//                ]);
//            }
//        } catch (ErrorException $exception) {
//            return response()->json([
//                'status' => false,
//                'data' => null,
//                'message' => $exception->getMessage()
//            ]);
//        }
//
//
//    }


    // - [ ] Post Request to connected Advisor
    public function updateConnectedAdvisor(Request $request)
    {

        try {

            $user = Auth::user();

            $adviser = MatchedAdvisorList::where('client_id', $user->id)->where('advisor_id', $request->advisor_id)->first();

            $advisorLog = User::where('id', $request->advisor_id)->first();
            if ($adviser->update(['advisor_request_date' => Carbon::now(), 'client_request_status' => 1])) {

                /* Email & Notification Section for Client successfully request for an advisor */
                if (!is_null($user)) {
                    $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                    $description = 'Advisor has sent you a 15 minute discovery call request.';
                    $fcmController->pushNotification('Congratulations!', $description, 'client', $advisorLog->id);
                    $html = "Congratulations! Your potential client has requested a 15 minute discovery call (to learn more about you and your approach to financial advising.) 
                            <br>Log back into the Guidefi app to review the potential client's information and to determine if you would like to accept the client.<br>
                            Advisors are expected to respond to client requests within 48-72 hours.
                            <br>For technical difficulties with your account, please email <br>";

                    EmailManager::sendMailQueue('Client Request', $html, $advisorLog->name , $advisorLog->email );
                }
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Request sent successfully!'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'something went wroung please try again'
                ]);
            }
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }
    }

    // [ ] Get All offered called by client
    // token will be client token and check will be the same as client_id = token id

    public function clientAdvisors()
    {
        try {

            $user = Auth::user(); // Client
            $data = [];

            $matchedRecord = MatchedAdvisorList::where('client_request_status', 2)->where('advisor_request_status', 1)
                ->where('client_id', $user->id)->with('appointment')->whereHas('advisor', function ($q) {
            })->get();


            if ($matchedRecord) {
                foreach ($matchedRecord as $record) {
                    if ($record->advisor->person) {
                        $callLog = null;
                        if ($record->appointment && $record->appointment->id) $callLog = $user->clientCallLogs()->where('advisor_id', $record->advisor_id)->where('appointment_id', $record->appointment->id)->first();

                        $offered_card = RatesCardOffers::with('ratecard')->where('client_id', $user->id)->where('advisor_id', $record->advisor_id)->latest('id')->first();
                        $ratecard = null;
                        if ($offered_card) {

                            $answers = $offered_card->ratecard->answers;
                            $servicesArray = $answers->filter(function ($query) {
                                return $query->question_text == 'Please indicate the financial product/services that your firm provides:';
                            });

                            $getServices = implode(', ', $servicesArray->pluck('answer_text')->toArray());

                            $notes = $answers->filter(function ($query) {
                                return $query->question_text == 'Notes';
                            });
                            $currentNotes = $notes->values();
                            $currentNotes = $currentNotes->toArray();

                            $getNotes = '';
                            if ($currentNotes && $currentNotes[0] && $currentNotes[0]['answer_text'] != '') {
                                $getNotes = $currentNotes[0]['answer_text'];
                            }

                            $ratecard = [
                                "offer_id" => $offered_card->id,
                                "ratecard_id" => $offered_card->rate_card_id,
                                "title" => ucwords($offered_card->offer_title),
                                "advisor_name" => $offered_card->ratecard->advisor_name,
                                "firm_name" => $offered_card->ratecard->firm_name,
                                "services" => $getServices,
                                "notes" => $getNotes,
                                "price" => floatval(preg_replace('/[^\d.]/', '', number_format($offered_card->amount, 2))),
                                "price_tag" => ucfirst($offered_card->price_tag)
                            ];
                        }

                        $offer_status = 'match';
                        if ($record->discovery_call) $offer_status = 'contract_offer';
                        if ($record->appointment && Carbon::parse($record->advisor_request_date)->addDays(2)->lt(Carbon::now())) $offer_status = 'expired';

                        if ($record->appointment && Carbon::parse($record->appointment->appointment_date . ' ' . $record->appointment->end_time)->lt(Carbon::now()))
                            $is_passed = 1;
                        else
                            $is_passed = 0;

                        $appointmentObj = $is_passed ? null : $record->appointment;

                        $data[] = [
                            'match_id' => $record->id,
                            'advisor_id' => intval($record->advisor_id),
                            'name' => $record->advisor->person->first_name . ' ' . $record->advisor->person->last_name,
                            'phone' => utf8_encode($record->advisor->person->phone),
                            'email' => utf8_encode($record->advisor->person->email),
                            'birth_date' => utf8_encode($record->advisor->person->birth_date),
                            'bio' => utf8_encode($record->advisor->person->bio),
                            'experience_level' => utf8_encode($record->advisor->person->experience_level),
                            'academic_records' => utf8_encode($record->advisor->person->academic_records),
                            'religion' => utf8_encode($record->advisor->person->religion),
                            'state' => utf8_encode($record->advisor->person->state),
                            'lang' => utf8_encode($record->advisor->person->lang),
                            'age' => utf8_encode($record->advisor->person->age),
                            'gender' => utf8_encode($record->advisor->person->gender),
                            'address' => $record->advisor->person->state . ' USA',
                            'discovery_call_done' => is_null($callLog) ? 0 : 1,
                            'rating' => ratingCalc($record->advisor),//4.50,
                            'imageUrl' => $record->advisor->person->image_url,
                            'offer_status' => $offer_status, // match, contract_offer (After discovery call) , expire.
                            'appointment' => $appointmentObj,
                            'ratecard' => $ratecard
                        ];

                       /* if($record->advisor) {
                            //Notify advisor if he/she has been chosen after discovery call
                            $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                            $description = 'Notify Email as you(advisor) is choosen';
                            $fcmController->pushNotification('Appointment Booked', $description, 'notify', $record->advisor->id);
                            if(!is_null($record->advisor)){
                                // Email & Notification Section for Confirmation of appointment for scheduled discovery call
                                EmailManager::sendMailQueue('Appointment Booked', $description, $record->advisor->name, $record->advisor->email);
                            }
                        }*/
                    }

                }


                return response()->json([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Advisor Successfully Retrieved!'
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => "",
                    'message' => 'No data found!'
                ]);
            }

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }


    }



    // [ ] Get All Offered by advisors
    // advisor token
    // token will be advisors token and check will be the same as advisor_id = token id
    public function advisorsOfferedClients()
    {
        try {

            $user = Auth::user(); // advisor
            $data = [];
            $matchedRecord = MatchedAdvisorList::where('client_request_status', 2)->where('advisor_request_status', 1)->where('advisor_id', $user->id)->with('appointment')->whereHas('client', function ($q) {
            })->get();
            if ($matchedRecord) {

                foreach ($matchedRecord as $record) {
                    if ($record->client->person) {

                        // If an offer is sent to the other user then find and attach with the request.
                        $callLog = null;
                        if ($record->appointment && $record->appointment->id) {
                            $callLog = $user->advisorCallLogs()->where('client_id', $record->client_id)->where('appointment_id', $record->appointment->id)->first();
                        }

                        $offered_card = RatesCardOffers::with('ratecard')->where('client_id', $record->client_id)->where('advisor_id', $user->id)->latest('id')->first();

                        $ratecard = null;
                        if ($offered_card) {

                            if ($offered_card->ratecard) {

                                $answers = $offered_card->ratecard->answers;
                                $servicesArray = $answers->filter(function ($query) {
                                    return $query->question_text == 'Please indicate the financial product/services that your firm provides:';
                                });

                                $getServices = implode(', ', $servicesArray->pluck('answer_text')->toArray());

                                $notes = $answers->filter(function ($query) {
                                    return $query->question_text == 'Notes';
                                });
                                $currentNotes = $notes->values();
                                $currentNotes = $currentNotes->toArray();

                                $getNotes = '';
                                if ($currentNotes && $currentNotes[0] && $currentNotes[0]['answer_text'] != '') {
                                    $getNotes = $currentNotes[0]['answer_text'];
                                }

                                $ratecard = [
                                    "offer_id" => $offered_card->id,
                                    "ratecard_id" => $offered_card->rate_card_id,
                                    "title" => ucwords($offered_card->offer_title),
                                    "advisor_name" => $offered_card->ratecard->advisor_name,
                                    "firm_name" => $offered_card->ratecard->firm_name,
                                    "services" => $getServices,
                                    "notes" => $getNotes,
                                    "price" => floatval(preg_replace('/[^\d.]/', '', number_format($offered_card->amount, 2))),
                                    "price_tag" => 'Monthly'
                                ];
                            }
                        }

                        $offer_status = 'match';
                        if ($record->discovery_call) $offer_status = 'contract_offer';
                        if ($record->appointment && Carbon::parse($record->advisor_request_date)->addDays(2)->lt(Carbon::now())) $offer_status = 'expired';

                        if ($record->appointment && Carbon::parse($record->appointment->appointment_date . ' ' . $record->appointment->end_time)->lt(Carbon::now()))
                            $is_passed = 1;
                        else
                            $is_passed = 0;

                        $appointmentObj = $is_passed ? null : $record->appointment;

                        // Client information
                        $data[] = [
                            "match_id" => $record->id,
                            "client_id" => $record->client_id, //$client->person->id,
                            "name" => $record->client->person->first_name . ' ' . $record->client->person->last_name,
                            "gender" => $record->client->person->gender,
                            "age" => $record->client->person->age . ' Years',
                            "income_level" => $record->client->clientRequest ? $record->client->clientRequest->income_level : "$100k/year",
                            "investment_level" => $record->client->clientRequest ? $record->client->clientRequest->investment_level : "$100k/year",
                            "assets" => $record->client->clientRequest ? $record->client->clientRequest->assets : "$100kyear",
                            "service" => $record->client->clientRequest ? $record->client->clientRequest->service : "Retirement Planning",
                            "request_date" => Carbon::parse($record->request_date)->format('d/m/Y'),
                            "expiry_time" => Carbon::parse($record->request_date)->diffInHours(Carbon::now()) . ' Hours',
                            'discovery_call_done' => is_null($callLog) ? 0 : 1,
                            'offer_status' => $offer_status, // match, contract_offer (After discovery call) , contract_offer_rejected
                            'appointment' => $appointmentObj,
                            'ratecard' => $ratecard
                        ];
                    }
                }
                return response()->json([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Advisor Successfully Retrieved!'
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => "",
                    'message' => 'No data found!'
                ]);
            }

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }


    }





    // [ ] Get My Clients
    // advisor token to get all contracted clients
    // token will be advisors token and check will be the same as advisor_id = token id
    public function myClients()
    {
        try {

            $user = Auth::user(); // advisor
            $data = [];
            $contractDetails = ClientAdvisorDeals::with('offer')->with('appointment')->with('match')->with('client')->where('advisor_id', $user->id)->get();

            if ($contractDetails) {

                foreach ($contractDetails as $record) {

                    if ($record->client->person) {

                        // If an offer is sent to the other user then find and attach with the request.
                        $callLog = null;
                        if ($record->appointment && $record->appointment->id) {
                            $callLog = $user->advisorCallLogs()->where('client_id', $record->client_id)->where('appointment_id', $record->appointment->id)->first();
                        }

                        $contract = null;
                        $contract_status = 'contract_ended';

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
                                    "end_date" => $contract_status == 'contract_ended' ? $record->deal_end_date : null,
                                    "contract_renew_time" => $record->offer->end_time,
                                    "status" => $record->offer->status,
                                    "offer_title" => $getServices,
                                    "price" => '$' . number_format($record->offer->amount, 2),
                                    "price_tag" => $record->offer->price_tag
                                ];
                            }
                        }

                        $offer_status = 'match';
                        if ($record->discovery_call) $offer_status = 'contract_offer';
                        if (Carbon::parse($record->advisor_request_date)->addDays(2)->lt(Carbon::now())) $offer_status = 'expired';

                        // Client information
                        $data[] = [
                            "id" => $record->client_id, //$client->person->id,
                            "name" => $record->client->person->first_name . ' ' . $record->client->person->last_name,
                            "image_url" => $record->client->person->image_url,
                            "contract_status" => $contract_status,
                            'offer_status' => $offer_status, // match, contract_offer (After discovery call) , contract_offer_rejected
                            'contract' => $contract,
                        ];
                    }
                }
                return response()->json([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Clients successfully retrieved!'
                ]);

                if (!is_null($advisor->user)) {
                    //Notify advisor of available client data to purchase

                    $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                    $description = 'Notify Email to view advisor discovery call status';
                    $fcmController->pushNotification('CHECK AVAILABLE DATA TO PURCHASE', $description, 'available data', $advisor->id);
                    if (!is_null($advisor->user)) {
                        /* Email & Notification Section for Notify advisor of available client data to purchase */
                        EmailManager::sendMailQueue('CHECK AVAILABLE DATA TO PURCHASE', $description, $advisor->user->name, $advisor->user->email);
                    }
                }
            } else {
                return response()->json([
                    'status' => true,
                    'data' => "",
                    'message' => 'No data found!'
                ]);
            }

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }


    }

    // - [ ] Schedule Discovery call
    public function discoveryCallStatus()
    {
        try {
            $user = Auth::user();
            $advisor = MatchedAdvisorList::where('advisor_id', $user->id)->where('client_request_status', 2)->where('advisor_request_status', 1)->first();
            if (!empty($advisor)) {
                if ($advisor->update(['discovery_call' => 1])) {
                    $slot = AppointmentAadvisorClient::create([
                        'advisor_id' => $advisor->advisor_id,
                        'advisort_timezone' => 'Asia/Karachi',
                        'client_id' => $advisor->client_id,
                        'client_timezone' => 'Asia/Karachi',
                        'appointment_status' => 'Booked',
                        'appointment_date' => '2020-06-26',
                        'start_time' => '04:00:00',
                        'end_time' => '04:30:00',
                        'appointment_day' => 'friday',
                        'type' => 'Audio',
                        'appointment_number' => '3214',
                        'timeslot_number' => '116',
                        'is_discovery_call' => 1,
                    ]);

                    if (!is_null($advisor->client)) {

                        //notify for appointment booking
                        $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                        $description = 'Notify Email to view advisor discovery call status';
                        $fcmController->pushNotification('Appointment Booked', $description, 'appointment', $advisor->client_id);

                        /* Email & Notification Section for appointment booking */
                        EmailManager::sendMailQueue('Appointment Booked', $description, $advisor->client->name, $advisor->client->email);

                    }
                    return response()->json([
                        'status' => true,
                        'data' => null,
                        'message' => 'Appointment Booked successfully'
                    ]);
                    //notify for reminding to add feedback
                    $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                    $description = 'kindly provide your feedback';
                    $fcmController->pushNotification('Feedback Request', $description, 'Feedback Request', $advisor->client_id);

                } else {

                    return response()->json([
                        'status' => false,
                        'data' => null,
                        'message' => 'something went wroung please try again'
                    ]);

                }
            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'No advisor found!'
                ]);
            }

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }
    }

    // - [ ] Get All Rates Cards
    public function AdvisorRateCards()
    {
        try {
            $user = Auth::user();
            $payload = [];
            $rateCards = RateCard::where('advisor_id', $user->id)->get();
            if (count($rateCards)) {
                foreach ($rateCards as $k => $card) {
                    $questions = Question::where('is_rate_card', 1)->get()->toArray();
                    $payload['card'][$k]['advisor_name'] = $card['advisor_name'];
                    $payload['card'][$k]['firm_name'] = $card['firm_name'];
                    foreach ($questions as $k1 => $question) {
                        $questions = Answers::where('question_id', $question['id'])->get();
                        $payload['card'][$k]['question'][$k1]['id'] = $question['id'];
                        $payload['card'][$k]['question'][$k1]['question_tag'] = $question['question_tag'];
                        $payload['card'][$k]['question'][$k1]['client_question_section_name'] = $question['client_question_section_name'];

                        $answers = Answers::where('question_id', $question['id'])->get()->toArray();
                        if (count($answers) > 0) {
                            foreach ($answers as $k2 => $answer) {
                                $payload['card'][$k]['question'][$k1]['answers'][$k2]['id'] = $answer['id'];
                                $payload['card'][$k]['question'][$k1]['answers'][$k2]['title'] = $answer['answer_text'];
                                $payload['card'][$k]['question'][$k1]['answers'][$k2]['question_text'] = $answer['question_text'];

                                $answersOptions = Options::where('option_id', $answer['option_id'])->where('rate_card_id', 1)->get()->toArray();
                                if (count($answersOptions) > 0) {
                                    foreach ($answersOptions as $k3 => $option) {
                                        $payload['card'][$k]['question'][$k1]['answers'][$k3]['option']['id'] = $option['id'];
                                        $payload['card'][$k]['question'][$k1]['answers'][$k3]['option']['title'] = $option['answer_label'];
                                        $payload['card'][$k]['question'][$k1]['answers'][$k3]['option']['answer_category'] = $option['answer_category'];
                                    }
                                }
                            }
                        }


                    }
                }

                return response()->json([
                    'status' => true,
                    'data' => $payload,
                    'message' => 'Data retrieved successfully!'
                ]);

            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'No Data found!'
                ]);
            }


        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }
    }


    // Send Rate Card Offer to Client
    public function sendOfferToClient(Request $request)
    {
        try {

            $user = Auth::user();

            $alreadySent = RatesCardOffers::where('advisor_id', $user->id)->where('client_id', $request->client_id)
                ->where('amount', $request->amount)->where('rate_card_id', $request->rate_card_id)
                ->where('offer_title', strtolower($request->offer_title))->first();

            if ($alreadySent) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'You already have sent same offer to this client.'
                ]);
            }

            $offerFount = RatesCardOffers::where('advisor_id', $user->id)->where('client_id', $request->client_id)
                ->first();
            if ($offerFount) {
                $offerFount->delete();
            }
            $offer = RatesCardOffers::create([
                'client_id' => $request->client_id,
                'advisor_id' => $user->id,
                'sent_time' => Carbon::now(),
                'amount' => $request->amount,
                'price_tag' => strtolower($request->price_tag),
                'offer_title' => strtolower($request->offer_title),
                'status' => 0,
                'rate_card_id' => $request->rate_card_id,
            ]);

            if ($offer) {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Offer Sent successfully!'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'something went wroung please try again'
                ]);
            }

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }

    }

    // Reject offer from a client
    public function rejectOffer(Request $request)
    {
        try {

            $user = Auth::user(); //Advisor

            $matchedRecord = MatchedAdvisorList::where('id', $request->match_id)->where('advisor_id', $user->id)->where('client_id', $request->client_id)->
            where('client_request_status', 1)->first();
            if (!$matchedRecord) {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'No matching found between you two.'
                ]);
            }
            $receiverId = $request->client_id;


            // NOTIFICATION TRIGGER after client confirmation of advisor selection
            $fcmController = new FcmController(env('FCM_SERVER_KEY'));
            $description = 'Advisor rejected your request. Please Contact Admin for further assistance!!';
            $response = $fcmController->pushNotification('offer Rejected', $description, 'feedback', $receiverId);
            if ($matchedRecord) {
                $matchedRecord->client_request_status = 3; // Reject an offer.
                $matchedRecord->decline_reason = $request->decline_reason;
                $matchedRecord->further_comments = $request->further_comments;
                $matchedRecord->save();

                if ($matchedRecord) {
                    return response()->json([
                        'status' => true,
                        'data' => null,
                        'message' => 'Offer Rejected!'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'data' => null,
                        'message' => 'something went wroung please try again'
                    ]);
                }
            }

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }

    }


    public function acceptMatch(Request $request)
    {
        try {
            $user = Auth::user();

            $stripeHelper = new StripeHelper();
            $card_id = $request->get('card_id');

            if ($request->get('payment_for') == 'client') {
                $client_id = $request->get('user_id');
                $advisor_id = $user->id;
            }

            $matchedRecord = MatchedAdvisorList::where('advisor_id', $advisor_id)->where('client_id', $client_id)->where('client_request_status', 1)->first();

            if (!$matchedRecord) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No matching found between you two.'
                ]);
            }



            if (is_null($user->customer_id) || $user->customer_id == '') {
                $customer = $stripeHelper->addCustomer($user);
            } else {
                $customer = $stripeHelper->getCustomer($user);
            }

            if (!$request->has('card_id') || $card_id == '') {
                $card = $stripeHelper->addCard($customer->id, $request);
            }

            else {
                $card = $stripeHelper->getCard($customer->id, $card_id);
            }

            if ($card && is_string($card)) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => $card
                ]);
            }


            if (!$customer->default_source || $request->get('is_default') == 1) {
                $customer->default_source = $card->id;
                $customer->save();
            }

            $currency = 'USD';


            $metadata = ["client_id" => $matchedRecord->client->id, 'client_name' => $matchedRecord->client->name, 'client_email' => $matchedRecord->client->email];


            $charge = $stripeHelper->createCharge($customer, $metadata, $request, $currency);


            if ($charge && is_string($charge)) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => $charge
                ]);
            }

            if ($charge) {
                if ($matchedRecord) {
                    $matchedRecord->client_request_status = 2; // payment done for this client
                    $matchedRecord->advisor_request_status = 1; // payment done for this client
                    $matchedRecord->save();

               /*     if (!is_null($user->name)) {

                        // EMAIL TRIGGER after client confirmation of advisor selection

                        EmailManager::sendMailQueue('CLIENT CONFIRMATION', 'Client Confirm your selection.', $user->name, $user->email);
                    }*/
                        // NOTIFICATION TRIGGER after client confirmation of advisor selection
                      /*  $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                        $description = $user->name . ' ' . 'has given you feedback.';
                        $response = $fcmController->pushNotification('Client Feedback', $description, 'feedback', $user->id);*/

                }

                $record = $stripeHelper->createStripeLog($user, $request, $card, $charge->id, $currency, null, null, $metadata);

                $clientInfo = User::where('id', $request->user_id)->first();

                if(!is_null($user->name)) {
                    $html = "Congratulations! You have successfully purchased the client information of ".$clientInfo->person->first_name .$clientInfo->person->last_name. " Log back into the Guidefi app to schedule a 15 minute discovery 
                    call with your new client. <br>Once you provide your schedule availability, your potential client will be able to select and confirm a time slot that works for their 
                    schedule. <br>Advisors are expected to respond to client requests within 48-72 hours. 
                     <br>For technical difficulties with your account, please email <br>";
//                    EmailManager::sendMailQueue('Request Accepted', $html, $user->name, $user->email);
                    EmailManager::sendMailQueue('Request Accepted', $html, $user->name, $user->email);
                }

//                $fcmController = new FcmController(env('FCM_SERVER_KEY'));
//                $description = $user->name . ' ' . 'has accepted your request.';
//                $response = $fcmController->pushNotification('Request Accepted', $description, 'request_feedback', $user->id);

                $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                $description = 'Advisor has accepted your matching request.';
                $response = $fcmController->pushNotification('Request Accepted', $description, 'request_feedback', $request->user_id);


                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Payment processed successfully.'
                ]);


            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Payment failed, Try Again later'
                ]);
            }


        } catch (ErrorException $e) {
            dd($e);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function book_appointment(Request $request)
    {
        try {
            $user = Auth::user();
            $advisor = User::find($request->get('advisor_id'));
            $input = $request->all();

            $AlreadyCalled = AdvisorClientAppointment::where('client_id', $user->id)->where('call_status', 1)->where('appointment_status', 'Booked')->where('advisor_id', $advisor->id)->first();

            if ($AlreadyCalled) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'A discovery call already done between you both.'
                ]);
            }

            $Alreadybooked = AdvisorClientAppointment::where('client_id', $user->id)->where('call_status', 0)->where('advisor_id', $advisor->id)->where('appointment_status', 'Booked')->first();

            if ($Alreadybooked) {
                AdvisorClientAppointment::where('client_id', $user->id)->where('call_status', 0)->where('appointment_status', 'Booked')->where('advisor_id', $advisor->id)->update(['appointment_status' => 'Canceled']);
            }

            $utilityClass = new Utility();
            $start_time = $request->has('start_time') ? $input['start_time'] : '00:00';
            $end_time = $request->has('end_time') ? $input['end_time'] : Carbon::parse($start_time)->addMinutes(30)->format('H:i');
            $date = $input['appointment_date'];
            $day = $request->has('appointment_day') ? $input['appointment_day'] : strtolower(Carbon::parse($date)->format('l'));
            $datetyme = date('Y-m-d H:i:s', strtotime("$date $start_time"));

            if (!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')) {
                $from = $request->get('timezone');
                $to = config('app.timezone');
                $response = $utilityClass->convertTimeZone($datetyme, $from, $to);
                $date = date('Y-m-d', strtotime($response));
                $day = Carbon::parse($response)->format('l');
                $start_time = date('H:i', strtotime($response));
                $end_time = Carbon::parse($start_time)->addMinutes(30)->format('H:i');
                $datetime = date('Y-m-d H:i:s', strtotime($response));
            }

            $monthNr = Carbon::parse($date)->month;

            $appointments_count = AdvisorClientAppointment::count();
            $appointments_count = sprintf("%04s", $appointments_count);

            $timing = AdvisorAvailablity::where('advisor_id', $request->get('advisor_id'))->where('day', $day)->first();
            if (!$timing) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Advisor not available on this day'
                ]);
            }
            $input['client_timezone'] = $request->get('timezone');
            $input['match_id'] = $request->get('match_id');
            $input['advisor_timezone'] = $timing->time_zone;


            $input['appointment_number'] = 'GDF' . $appointments_count;

            $input['appointment_date'] = $date;
            $input['appointment_day'] = $day;
            $input['start_time'] = $start_time;
            $input['end_time'] = $end_time;
            $input['appointment_status'] = 'Booked';
            $input['is_archive'] = 0;
            $input['is_discovery_call'] = 1;
            $input['call_status'] = 0;
            $input['client_service_id'] = 1;


            DB::transaction(function () use ($input, $user, $advisor) {
                $appointment = AdvisorClientAppointment::create($input);
                $appointment->client()->associate($user)->save();
                $appointment->advisor()->associate($advisor)->save();
            });


            if (!is_null(config('app.timezone')) && !is_null($timing->time_zone) && $timing->time_zone !== config('app.timezone')) {
                $utilityClass = new Utility();
                $time = $datetime;
                $from = config('app.timezone');
                $to = $timing->time_zone;
                $time = $utilityClass->convertTimeZone($time, $from, $to);
                $datetime = Carbon::parse($time)->format('m/d/Y h:i A');
            }
            if (!is_null($advisor->name)) {

                //notify for appointment booking
                $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                $description = "Your client,(".$user->name .") had scheduled a discovery call for (".Carbon::parse($datetime)->format('m/d/Y').") and (".Carbon::parse($datetime)->format('h:i A').
                    "). Log into the Guidefi app in order to reschedule your discovery call.
                   <br>For technical difficulties with your account, please email <br>";
                $fcmController->pushNotification('Scheduled Discovery Call', $description, 'discovery_call', $advisor->user_id);

                //email for appointment booking
                EmailManager::sendMailQueue('Scheduled Discovery Call', $description, $advisor->name, $advisor->email);
//                EmailManager::sendMailQueue('Scheduled Discovery Call', $description, $user->name, $user->email);

            }
            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Appointment for discovery call is Booked!'
            ]);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }
    }

    /*
* New appointment system
* */
    public function gettimingTemp(Request $request)
    {
        try {
            $timeSlotsFormat = TimeSlotsFormat::get();

            if (!$request->has('day')) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Day required'
                ]);
            }
            $user = Auth::user();
            if ($user->isClient()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Client is not allowed to get time slots.'
                ]);
            }

            $day = $request->get('day');
            $available = null;
            $AdvisorAvailablity = AdvisorAvailablity::where('day', strtolower($day))->where('advisor_id', $user->id)->first();
            if ($AdvisorAvailablity) $available = TimeSlots::where('start_time', $AdvisorAvailablity->start_time)->where('end_time', $AdvisorAvailablity->end_time)->first();

            if ($timeSlotsFormat) {
                return response()->json([
                    'status' => true,
                    'data' => $timeSlotsFormat,
                    'selected_template_id' => $available ? $available->time_slot_template_id : 0,
                    'message' => 'All Templates retrieved successfully'
                ]);

            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'There is something wrong, please try again.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }

    }


    public function getAdvisorTimeSlot(Request $request)
    {
        try {
            $user = Auth::user();


            $this->validate($request, [
                'date' => 'required',
                'timezone' => 'required',
                'advisor_id' => 'required|exists:users,id'
            ]);

            $date = Carbon::parse($request->get('date'))->format('Y-m-d');
            $day = Carbon::parse($date)->format('l');

            $timings = AdvisorAvailablity::where('advisor_id', $request->get('advisor_id'))->where('day', $day)->get();
            if (!$timings) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Advisor not available on this ' . $day
                ]);
            }
            $timeslots = [];
            $utilityClass = new Utility();

            foreach ($timings as $key => $value) {
                $date = Carbon::parse($request->get('date'))->format('Y-m-d');
                $slot_start_time = $value->start_time;
                $timezone = $value->time_zone;
                $slot_end_time = $value->end_time;

                $booked_timeslots = AdvisorClientAppointment::where('client_id', $user->id)->where('appointment_status', 'Booked')->where('timeslot_number', $value->id)->whereDate('appointment_date', 'like', $date)->first();
                $is_booked = 0;
                if (!is_null($booked_timeslots)) $is_booked = 1;

                if (Carbon::parse($date . ' ' . $slot_start_time)->lt(Carbon::now()))
                    $is_passed = 1;
                else
                    $is_passed = 0;

                if (!is_null($timezone) && $request->get('timezone') !== $timezone) {

                    $time = $date . ' ' . $slot_start_time;
                    $from = $timezone;
                    $to = $request->get('timezone');
                    $time = $utilityClass->convertTimeZone($time, $from, $to);
                    $date = date("Y-m-d", strtotime($time));
                    $slot_start_time = date("H:i", strtotime($time));
                    $slot_end_time = Carbon::parse($slot_start_time)->addMinutes(30)->format("H:i");
                }


                $timeslots[] = [
                    'label' => Carbon::parse($slot_start_time)->format("h:i A") . ' - ' . Carbon::parse($slot_end_time)->format("h:i A"), // updated start time.
                    'start_time' => Carbon::parse($slot_start_time)->format("H:i"), // updated start time.
                    'end_time' => Carbon::parse($slot_end_time)->format("H:i"), // updated start time.
                    'id' => $value->id,
                    'is_booked' => $is_booked,
                    'is_passed' => $is_passed,
                    'day' => $day,
                    'date' => $date, // updated date.
                ];
            }
            if (count($timeslots) > 0) {
                return $this->sendResponse($timeslots, 'Time Slots retrieved successfully');
            } else {
                return $this->sendResponse([], 'Advisor is not available.');
            }
        } catch (\Exception $e) {
            error_log($e);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getTimeSlotsNew(Request $request)
    {
        try {


            $user = Auth::user();
            if ($user->isClient()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Client is not allowed to get time slots.'
                ]);
            }

            $day = $request->get('day');
            $AdvisorAvailablity = AdvisorAvailablity::where('day', strtolower($day))->where('advisor_id', $user->id)->get();

            $available = TimeSlots::where('time_slot_template_id', $request->get('timeslot'))->get();

            $alreadyTakenDay = 0;
            if ($AdvisorAvailablity) {
                $alreadyTakenDay = 1;
            }
            $timeSlots = [];
            if ($AdvisorAvailablity) {
                foreach ($available as $key => $value) {

                    $value->isSelected = 0;
                    unset($value->deleted_at);
                    if ($alreadyTakenDay) {
                        $match = $AdvisorAvailablity->filter(function ($filterVal, $key) use ($value) {
                            return ($filterVal['start_time'] == $value->start_time && $filterVal['end_time'] == $value->end_time);
                        });
                        if (count($match) > 0) {
                            $value->isSelected = 1;
                        }

                        $value->start_time = Carbon::parse($value->start_time)->format('H:i');
                        $value->end_time = Carbon::parse($value->end_time)->format('H:i');
                    }
                    $timeSlots[] = $value;
                }


                return $this->sendResponse($timeSlots, 'All Templates retrieved successfully');

            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'There is something wrong, please try again.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }

    }


    public function saveAdvisorAvailability(Request $request)
    {
        try {

            $data = $request->all();

            $user = Auth::user();
            if ($user->isClient()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Client is not allowed to save availability.'
                ]);
            }

            $tmpData = [];
            $recods = [];
            foreach ($data['timeslots'] as $key => $value) {
                $tmpData['day'] = strtolower($data['day']);
                $tmpData['time_zone'] = $data['timezone'];
                $tmpData['advisor_id'] = $user->id;
                $tmpData['start_time'] = Carbon::parse($value['start_time'])->format('H:i');
                $tmpData['end_time'] = Carbon::parse($value['end_time'])->format('H:i');
                $recods[] = $tmpData;
            }

            $advisorAvailablity = AdvisorAvailablity::where('day', strtolower($data['day']))->where('advisor_id', $user->id)->get();

            if ($advisorAvailablity) {
                AdvisorAvailablity::where('day', strtolower($data['day']))->where('advisor_id', $user->id)->delete();
            }

            $advisorAvailablity = AdvisorAvailablity::insert($recods);


            if ($advisorAvailablity) {
                return $this->sendResponse($advisorAvailablity, 'Created successfully.');

            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'There is something wrong, please try again.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }

    }


    public function savetimeSlots(Request $request)
    {

        try {
            $users = User::whereHas('roles', function ($q) {
                $q->where('slug', 'advisor');
            })->get();
            $index = 0;
            foreach ($users as $key => $user) {

                $advisorrequest = Advisorrequest::where('user_id', $user->email)
                    ->where('status', 'Approved')->first();

//                $user2 = User::find($user->id);
                if (!is_null($advisorrequest)) {
                    $index++;

                    if ($user->therapist) {
                        $advisorrequest->therapist_id = $user->therapist->id;
                        $advisorrequest->save();
                        $advisorrequests = Advisorrequest::where('user_id', $user->email)
                            ->where('id', '<>', $advisorrequest->id)
                            ->update(['status' => 'Pending', 'therapist_id' => 0]);
                    } else {
                        $Advisorrequests = Advisorrequest::where('email', $user->email)
                            ->whereNull('therapist_id')
                            ->update(['status' => 'Pending', 'therapist_id' => 0]);
                    }
                }
            }

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Total ' . $index . ' requests updated.'
            ]);

        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }

        dd($users);

        if ($request->has('appointment') && $request->get('appointment') == 1) {
            AdvisorClientAppointment::query()->truncate();
            $oldAppointments = Appointment::withTrashed()->get();

            $recods = [];
            foreach ($oldAppointments as $key => $appointment) {
                $tmpData['advisor_id'] = $appointment->advisor_id;
                $tmpData['client_id'] = $appointment->user_id;
                $tmpData['appointment_date'] = $appointment->datetime;
                $tmpData['timeslot_number'] = $appointment->timeslot_number;
                $tmpData['client_timezone'] = $appointment->client_timezone;
                $tmpData['therapist_timezone'] = $appointment->therapist_timezone;
                $tmpData['type'] = $appointment->type;
                $tmpData['created_at'] = $appointment->created_at;
                $tmpData['updated_at'] = $appointment->updated_at;
                $tmpData['appointment_number'] = $appointment->appointment_number;
                $tmpData['deleted_at'] = $appointment->deleted_at;
                $tmpData['appointment_status'] = 'Booked';
                $tmpData['start_time'] = Carbon::parse($appointment->datetime)->format('H:i');
                $tmpData['end_time'] = Carbon::parse($appointment->datetime)->addMinutes(30)->format('H:i');
                $tmpData['appointment_day'] = Carbon::parse($appointment->datetime)->format('l');

                if (is_null($appointment->therapist_timezone)) $tmpData['therapist_timezone'] = config('app.timezone');
                if (is_null($appointment->client_timezone)) $tmpData['client_timezone'] = config('app.timezone');
                if (!is_null($appointment->deleted_at)) $tmpData['appointment_status'] = 'Canceled';

                $recods[] = $tmpData;

            }
            AdvisorClientAppointment::insert($recods);
        }
        $TimeSlots = TimeSlots::query()->truncate();
        $time = strtotime('00:00');
        for ($k = 1; $k <= 2; $k++) {
            if ($k == 2) {
                $time = strtotime('00:15');
            }
            for ($i = 1; $i <= 48; $i++) {
                $startTime = date("H:i", strtotime('-0 minutes', $time));
                $endTime = date("H:i", strtotime('+30 minutes', $time));
                TimeSlots::create(
                    [
                        'label' => $startTime . ' - ' . $endTime,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'time_slot_template_id' => $k,
                    ]
                );
                $time = strtotime('+30 minutes', $time);
            }
        }

        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'Added all the time slots into the table.'
        ]);

    }


    // This method is to view all set timeslots
    public function getActiveTimeslots(Request $request)
    {
        try {

            if (!$request->has('day')) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Day required'
                ]);
            }
            $user = Auth::user();
            if ($user->isClient()) {
                $advisor_id = $request->get('advisor_id');
            } else {
                $advisor_id = $user->id;
            }
            $advisorAvailablity = AdvisorAvailablity::where('day', strtolower($request->get('day')))->where('advisor_id', $advisor_id)->get();
            if ($advisorAvailablity) {
                $advisorAvailablity->filter(function ($value, $key) {
                    $value->start_time = Carbon::parse($value->start_time)->format('H:i');
                    $value->end_time = Carbon::parse($value->end_time)->format('H:i');
                    return $value->label = Carbon::parse($value->start_time)->format('h:i A') . ' - ' . Carbon::parse($value->end_time)->format('h:i A');
                });
                return $this->sendResponse($advisorAvailablity, 'Retrived successfully.');

            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'There is something wrong, please try again.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }

    }


    public function AdvisorProfile(Request $request)
    {
        try {

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());
            $user = Auth::user();


            if ($request->has('user_id') && $request->user_id) {
                $id = $request->user_id;
            } else {
                $id = Auth::user()->id;
            }

            $advisor = User::with('advisorrequest')->where('id', $id)->first();

            if ($advisor->isClient()) {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'You are Client. Try with an advisor.'
                ]);
            }

            $records = new Item($advisor, new AdvisorTransformer());
            $manager = $manager->createData($records);

            $callLog = null;

            // get a user active appointment.
            // then find a call on the bases of that.

            $callLog = null;

            // get a user active appointment.
            // then find a call on the bases of that.

            $returnRec = $manager->toArray();
            if ($user->isClient()) {
                $appointment = AdvisorClientAppointment::where('client_id', $user->id)->where('advisor_id', $id)->where('appointment_status', 'Booked')->first();
                $returnRec['discovery_call_done'] = 0;

                if ($appointment && Carbon::parse($appointment->appointment_date . ' ' . $appointment->end_time)->lt(Carbon::now())) {
                    if ($appointment && $appointment->call_status == 0) {
                        $returnRec['discovery_call_done'] = 0;
                    } else if ($appointment && $appointment->call_status == 1) {
                        $callLog = $advisor->advisorCallLogs()->where('appointment_id', $appointment->id)->where('client_id', $user->id)->first();
                        $returnRec['discovery_call_done'] = !is_null($callLog) ? 1 : 0;
                    }
                }


                ProfileView::create([
                    'client_id' => $user->id,
                    'advisor_id' => $advisor->id,
                    'client_ip' => $request->ip()

                ]);


                $contractDetails = ClientAdvisorDeals::with('appointment')->with('offer')->with('match')->with('advisor')->with('client')->where('client_id', $user->id)->where('advisor_id', $id)->latest('id')->first();
                $feedbackObj = null;
                $feedback = FeedbackDealFinish::where('client_id', $user->id)->latest('id')->first();
                if (!is_null($feedback)) {
                    $feedbackObj = [
                        "id" => $feedback->id,
//                        "client_advisor_deal_id" => $feedbacks->offer->id,
                        "client" => !is_null($feedback->client) ? $feedback->client->name : null,
                        "advisor" => !is_null($feedback->advisor) ? $feedback->advisor->name : null,
                        "expertise_level" => !is_null($feedback->expertise_level) ? $feedback->expertise_level : null,
                        "availability" => !is_null($feedback->availability) ? $feedback->availability : null,
                        "communication" => !is_null($feedback->communication) ? $feedback->communication : null,
                        "feedback" => !is_null($feedback->feedback) ? $feedback->feedback : null,
                        "feedback_by" => !is_null($feedback->feedback_by) ? $feedback->feedback_by : null,
                        "created_at" => !is_null($feedback->created_at) ? $feedback->created_at : null,
                    ];
                }

                $returnRec['status'] = 'contract_ended';
                if ($contractDetails) {
                    $returnRec['status'] = 'contract_active';
                    if ($contractDetails->match->client_request_status == 2 && $contractDetails->match->advisor_request_status == 1) {
                        $returnRec['status'] = 'offered';
                    } else {
                        $returnRec['status'] = 'not-offered';
                    }
                }

                $contract = null;
                if ($contractDetails && $contractDetails->offer) {

                    if ($contractDetails->offer->ratecard) {

                        $answers = $contractDetails->offer->ratecard->answers;
                        $servicesArray = $answers->filter(function ($query) {
                            return $query->question_text == 'Please indicate the financial product/services that your firm provides:';
                        });

                        $getServices = implode(', ', $servicesArray->pluck('answer_text')->toArray());
                        $returnRec['contract_status'] = $contractDetails->status ? 'contract_active' : 'contract_ended';

                        $contract = [
                            "id" => $contractDetails->id,
                            "offer_id" => $contractDetails->offer->id,
                            "appointment_id" => $contractDetails->appointment_advisor_client_id,
                            "topic" => $getServices,
                            "start_date" => $contractDetails->deal_start_date,
                            "end_date" => $returnRec['contract_status'] == 'contract_ended' ? $contractDetails->deal_end_date : null,
                            "contract_renew_time" => $contractDetails->offer->end_time,
                            "status" => $contractDetails->offer->status,
                            "offer_title" => $getServices,
                            "price" => '$' . number_format($contractDetails->offer->amount, 2),
                            "price_tag" => $contractDetails->offer->price_tag
                        ];

                    }

                } else {
                    $returnRec['contract_status'] = $contractDetails ? 'contract_ended' : '';
                }

                if ($appointment) {
                    if (Carbon::parse($appointment->appointment_date . ' ' . $appointment->end_time)->lt(Carbon::now()))
                        $is_passed = 1;
                    else
                        $is_passed = 0;
                } else {
                    $is_passed = 0;
                }

                $returnRec['appointment'] = $is_passed ? null : $appointment;
                $returnRec['contract'] = $contract;
                $returnRec['feedback'] = $feedbackObj;
            }

            if ($records) {
                return response()->json([
                    'status' => true,
                    'data' => $returnRec,
                    'message' => 'successfully retrieved!'
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'No Record Found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => "",
                'message' => $e->getMessage()
            ]);
        }

    }


    public function AdvisorTimings()
    {
        try {

            $user = Auth::user();
            $timings = AdvisorAvailablity::where('advisor_id', $user->id)->get();
            if (!is_null($timings) && $timings) {
                $tmp = [];
                $days = [];
                foreach ($timings as $key => $timing) {
                    if (!in_array($timing->day, $days)) {
                        /*$end_time1 = $timings->filter(function ($value, $key) use($timing) {
                            return $value->day == $timing->day;
                        });*/
                        $start_time = AdvisorAvailablity::where('advisor_id', $timing->advisor_id)->where('day', $timing->day)->orderBy('start_time', 'asc')->first();
                        $end_time = AdvisorAvailablity::where('advisor_id', $timing->advisor_id)->where('day', $timing->day)->orderBy('end_time', 'desc')->first();
                        $tmp[] = [
                            'id' => $timing->id,
                            'advisor_id' => $timing->advisor_id,
                            'day' => $timing->day,
                            'start_time' => Carbon::parse($start_time->start_time)->format('h:i A'),
                            'end_time' => Carbon::parse($end_time->end_time)->format('h:i A'),
                            'time_zone' => $timing->time_zone,
                            'created_at' => Carbon::parse($timing->created_at)->format('m/d/Y h:i A'),
                            'updated_at' => Carbon::parse($timing->created_at)->format('m/d/Y h:i A')
                        ];
                        $days[] = $timing->day;
                    }

                }

                return response()->json([
                    'status' => true,
                    'data' => $tmp,
                    'message' => 'successfully retrieved!'
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'successfully retrieved!'
                ]);
            }


        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => "",
                'message' => $e->getMessage()
            ]);
        }


    }


    public function oneTime()
    {
        try {
            $TimeSlots = TimeSlots::query()->truncate();
            $time = strtotime('06:00');
            for ($k = 1; $k <= 2; $k++) {
                if ($k == 2) {
                    $time = strtotime('00:15');
                }
                for ($i = 1; $i <= 30; $i++) {
                    $startTime = date("H:i", strtotime('-0 minutes', $time));
                    $endTime = date("H:i", strtotime('+30 minutes', $time));
                    TimeSlots::create(
                        [
                            'label' => date("h:i A", strtotime('-0 minutes', $time)) . ' - ' . date("h:i A", strtotime('+30 minutes', $time)),
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'time_slot_template_id' => $k,
                        ]
                    );
                    $time = strtotime('+30 minutes', $time);
                }
            }
            return response()->json([
                'status' => true,
                'data' => "",
                'message' => 'Added'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => "",
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * analytics of profile views time period wise
     */

    public function profileViews(Request $request)
    {
        try {
            $user = Auth::user();
            $lowerAction = strtolower($request->get('time_period'));

            if ($user->isAdvisor()) {
                if ($lowerAction == 'this year') {
                    $year = Carbon::today();

                    $views = ProfileView::selectRaw('year(created_at) year, monthname(created_at) month, count(*) views')
                        ->where('advisor_id', $user->id)
                        ->whereYear('created_at', $year)
                        ->groupBy('year', 'month')
                        ->get();

                    $data = array();
                    $rowno = 1;

                    foreach ($views as $view) {

                        $week = Carbon::parse($view->month);

                        while ($rowno <= $week->format('m')) {
                            if ($rowno == $week->format('m')) {
                                $viewObj = array(
                                    'type' => "this year",
                                    'views' => $view->views,
                                    'year' => strval($view->year),
                                    'month' => substr($view->month, 0, 3),
                                    'week' => null,
                                );
                            } else {
                                $month_name = date("F", mktime(0, 0, 0, $rowno, 10));
                                $month_name = substr($month_name, 0, 3);
                                $viewObj = array(
                                    'type' => "this year",
                                    'views' => 0,
                                    'year' => strval($view->year),
                                    'month' => $month_name,
                                    'week' => null,
                                );
                            }
                            array_push($data, $viewObj);
                            $rowno++;
                        }

                        $rowno = $week->format('m');
                        $rowno++;
                    }

                    return response()->json([
                        'status' => true,
                        'data' => $data,
                        'message' => "views of this month"
                    ]);

                } elseif ($lowerAction == 'this month') {
                    $currentDate = Carbon::today();
                    $views = ProfileView::selectRaw("COUNT(*) views, WEEKOFYEAR(created_at) AS week")
                        ->where('advisor_id', $user->id)
                        ->whereMonth('created_at', $currentDate)
                        ->whereYear('created_at', $currentDate)
                        ->groupBy('week')
                        ->get();

                    $month = Carbon::parse($currentDate)->format('M Y');

                    $data = array();
                    $rowno = 1;

                    foreach ($views as $key => $view) {

                        $week = Carbon::now()->setISODate($currentDate->year, $view->week);

                        while ($rowno <= $week->weekOfMonth) {
                            if ($rowno == $week->weekOfMonth) {
                                $viewObj = array(
                                    'type' => "this month",
                                    'views' => $view->views,
                                    'year' => null,
                                    'month' => $month,
                                    'week' => "Week " . $week->weekOfMonth,
                                );
                            } else {
                                $viewObj = array(
                                    'type' => "this month",
                                    'views' => 0,
                                    'year' => null,
                                    'month' => $month,
                                    'week' => "Week " . $rowno,
                                );
                            }
                            array_push($data, $viewObj);
                            $rowno++;
                        }

                        $rowno = $week->weekOfMonth;
                        $rowno++;
                    }

                    $today = Carbon::today()->format('Y-m-d');
                    $weekOfMonth = Carbon::createFromDate($today)->weekOfMonth;
                    while ($rowno <= $weekOfMonth) {
                        $viewObj = array(
                            'type' => "this month",
                            'views' => 0,
                            'year' => null,
                            'month' => $month,
                            'week' => "Week " . $rowno,

                        );

                        array_push($data, $viewObj);
                        $rowno++;
                    }

                    return response()->json([
                        'status' => true,
                        'data' => $data,
                        'message' => "views of this month"
                    ]);

                } elseif ($lowerAction == 'last month') {
                    $date = Carbon::now();
                    $lastMonth = $date->subMonth();
                    $views = ProfileView::selectRaw("COUNT(*) views, WEEKOFYEAR(created_at) week")
                        ->where('advisor_id', $user->id)
                        ->whereMonth('created_at', $lastMonth->format('m'))
                        ->whereYear('created_at', $lastMonth->format('Y'))
                        ->groupBy('week')
                        ->get();


                    $month = Carbon::parse($lastMonth)->format('M Y');

                    $data = array();
                    $rowno = 1;

                    foreach ($views as $key => $view) {

                        $week = Carbon::now()->setISODate($lastMonth->year, $view->week);

                        while ($rowno <= $week->weekOfMonth) {
                            if ($rowno == $week->weekOfMonth) {
                                $viewObj = array(
                                    'type' => "last month",
                                    'views' => $view->views,
                                    'year' => null,
                                    'month' => $month,
                                    'week' => "Week " . $week->weekOfMonth,
                                );
                            } else {
                                $viewObj = array(
                                    'type' => "last month",
                                    'views' => 0,
                                    'year' => null,
                                    'month' => $month,
                                    'week' => "Week " . $rowno,
                                );
                            }
                            array_push($data, $viewObj);
                            $rowno++;
                        }

                        $rowno = $week->weekOfMonth;
                        $rowno++;
                    }

                    $today = $lastMonth->format('Y-m-d');
                    $weekOfMonth = Carbon::createFromDate($today)->weekOfMonth;
                    while ($rowno <= $weekOfMonth) {
                        $viewObj = array(
                            'type' => "last month",
                            'views' => 0,
                            'year' => null,
                            'month' => $month,
                            'week' => "Week " . $rowno,
                        );

                        array_push($data, $viewObj);
                        $rowno++;
                    }

                    return response()->json([
                        'status' => true,
                        'data' => $data,
                        'message' => "views of this month"
                    ]);

                } elseif ($lowerAction == 'current quarter') {

                    for ($i = 0; $i < 12; $i++) {
                        $dt = Carbon::now()->endOfYear()->startOfMonth()->subMonths($i);
                        $quarter['Q' . $dt->quarter][] = $dt->format('M Y');
                    }

                    $currentQuarter = Carbon::now()->format('M Y');

                    if (isset($quarter['Q1']) && in_array($currentQuarter, $quarter['Q1'])) {
                        $year = Carbon::today();
                        $views = ProfileView::selectRaw('quarter(created_at) quarter, monthname(created_at) month, count(*) data')
                            ->where('advisor_id', $user->id)
                            ->whereMonth('created_at', '>=', '1')
                            ->whereMonth('created_at', '<=', '3')
                            ->whereYear('created_at', $year)
                            ->groupBy('quarter', 'month')
                            ->get();
                        $data = array();
                        $rowno = 1;
                        foreach ($views as $view) {
                            $month = Carbon::parse($view->month);

                            while ($rowno <= 3 && $rowno >= 1) {
                                if ($rowno == $month->format('m')) {
                                    $viewObj = array(
                                        'type' => "current quarter",
                                        'views' => $view->views,
                                        'year' => strval($year->format('Y')),
                                        'month' => $view->month,
                                        'week' => null,
                                    );
                                } else {
                                    $month_name = date("F", mktime(0, 0, 0, $rowno, 10));
                                    $viewObj = array(
                                        'type' => "current quarter",
                                        'views' => 0,
                                        'year' => strval($year->format('Y')),
                                        'month' => $month_name,
                                        'week' => null,
                                    );
                                }
                                array_push($data, $viewObj);
                                $rowno++;
                            }

                            $rowno = $month->weekOfMonth;
                            $rowno++;
                        }

                        return response()->json([
                            'status' => true,
                            'data' => $data,
                            'message' => "views of this month"
                        ]);
                    } elseif (isset($quarter['Q2']) && in_array($currentQuarter, $quarter['Q2'])) {
                        $year = Carbon::today();

                        $views = ProfileView::selectRaw('quarter(created_at) quarter, monthname(created_at) month, count(*) views')
                            ->where('advisor_id', $user->id)
                            ->whereMonth('created_at', '>=', '4')
                            ->whereMonth('created_at', '<=', '6')
                            ->whereYear('created_at', $year)
                            ->groupBy('quarter', 'month')
                            ->get();

                        $data = array();
                        $rowno = 4;
                        foreach ($views as $view) {
                            $month = Carbon::parse($view->month);

                            while ($rowno <= 6 && $rowno >= 4) {
                                if ($rowno == $month->format('m')) {
                                    $viewObj = array(
                                        'type' => "current quarter",
                                        'views' => $view->views,
                                        'year' => strval($year->format('Y')),
                                        'month' => $view->month,
                                        'week' => null,
                                    );
                                } else {
                                    $month_name = date("F", mktime(0, 0, 0, $rowno, 10));
                                    $viewObj = array(
                                        'type' => "current quarter",
                                        'views' => 0,
                                        'year' => strval($year->format('Y')),
                                        'month' => $month_name,
                                        'week' => null,
                                    );
                                }
                                array_push($data, $viewObj);
                                $rowno++;
                            }

                            $rowno = $month->weekOfMonth;
                            $rowno++;
                        }

                        return response()->json([
                            'status' => true,
                            'data' => $data,
                            'message' => "views of this month"
                        ]);

                    } elseif (isset($quarter['Q3']) && in_array($currentQuarter, $quarter['Q3'])) {
                        $year = Carbon::today();

                        $views = ProfileView::selectRaw('quarter(created_at) quarter, monthname(created_at) month, count(*) views')
                            ->where('advisor_id', $user->id)
                            ->whereMonth('created_at', '>=', '7')
                            ->whereMonth('created_at', '<=', '9')
                            ->whereYear('created_at', $year)
                            ->groupBy('quarter', 'month')
                            ->get();

                        $data = array();
                        $rowno = 7;
                        foreach ($views as $view) {
                            $month = Carbon::parse($view->month);

                            while ($rowno <= 9 && $rowno >= 7) {
                                if ($rowno == $month->format('m')) {
                                    $viewObj = array(
                                        'type' => "current quarter",
                                        'views' => $view->views,
                                        'year' => strval($year->format('Y')),
                                        'month' => $view->month,
                                        'week' => null,
                                    );
                                } else {
                                    $month_name = date("F", mktime(0, 0, 0, $rowno, 10));
                                    $viewObj = array(
                                        'type' => "current quarter",
                                        'views' => 0,
                                        'year' => strval($year->format('Y')),
                                        'month' => $month_name,
                                        'week' => null,
                                    );
                                }
                                array_push($data, $viewObj);
                                $rowno++;
                            }

                            $rowno = $month->weekOfMonth;
                            $rowno++;
                        }

                        return response()->json([
                            'status' => true,
                            'data' => $data,
                            'message' => "views of this month"
                        ]);
                    } elseif (isset($quarter['Q4']) && in_array($currentQuarter, $quarter['Q4'])) {
                        $year = Carbon::today();
                        $views = ProfileView::selectRaw('quarter(created_at) quarter, monthname(created_at) month, count(*) views')
                            ->where('advisor_id', $user->id)
                            ->whereMonth('created_at', '>=', '10')
                            ->whereMonth('created_at', '<=', '12')
                            ->whereYear('created_at', $year)
                            ->groupBy('quarter', 'month')
                            ->get();

                        $data = array();
                        $rowno = 10;
                        foreach ($views as $view) {
                            $month = Carbon::parse($view->month);

                            while ($rowno <= 12 && $rowno >= 10) {
                                if ($rowno == $month->format('m')) {
                                    $viewObj = array(
                                        'type' => "current quarter",
                                        'views' => $view->views,
                                        'year' => strval($year->format('Y')),
                                        'month' => $view->month,
                                        'week' => null,
                                    );
                                } else {
                                    $month_name = date("F", mktime(0, 0, 0, $rowno, 10));
                                    $viewObj = array(
                                        'type' => "current quarter",
                                        'views' => 0,
                                        'year' => strval($year->format('Y')),
                                        'month' => $month_name,
                                        'week' => null,
                                    );
                                }
                                array_push($data, $viewObj);
                                $rowno++;
                            }

                            $rowno = $month->weekOfMonth;
                            $rowno++;
                        }

                        return response()->json([
                            'status' => true,
                            'data' => $data,
                            'message' => "views of this month"
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'data' => "",
                    'message' => "Something went wrong"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => "",
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * Analytics of incomes monthy yearly wise
     */

    public function analyticsIncome(Request $request)
    {
        try {

            $user = Auth::user();
            $lowerAction = strtolower($request->get('time_period'));

            if ($lowerAction == 'month') {
                $month = $request->get('month');
                $year = Carbon::today();
                $amounts = StripePaymentLog::selectRaw("sum(amount) amounts, DATE_FORMAT(created, '%Y-%m-%e') date")
                    ->where('advisor_id', $user->id)
                    ->whereYear('created', $year)
                    ->whereMonth('created', $month)
                    ->groupBy('date')
                    ->get();


                if (isset($amounts) && count($amounts) > 0) {

                    $data = array();

                    foreach ($amounts as $amount) {
                        $viewObj = array(
                            'type' => "month",
                            'amount' => $amount->amounts,
                            'day' => Carbon::parse($amount->date)->format('d'),
                        );
                    }
                    array_push($data, $viewObj);

                    return response()->json([
                        'status' => true,
                        'data' => $data,
                        'message' => ""
                    ]);
                } else {


                    return response()->json([
                        'status' => true,
                        'data' => [],
                        'message' => ""
                    ]);
                }


            } elseif ($lowerAction == 'year') {

                $currentYear = Carbon::today()->format('Y');
                $amounts = StripePaymentLog::selectRaw("sum(amount) amounts,  monthname(created) month")
                    ->where('advisor_id', $user->id)
                    ->whereYear('created', $currentYear)
                    ->groupBy('month')
                    ->orderBy('month', 'Desc')
                    ->get();

                $data = array();
                $rowno = 1;

                foreach ($amounts as $amount) {

                    $week = Carbon::parse($amount->month);

                    while ($rowno <= $week->format('m')) {
                        if ($rowno == $week->format('m')) {

                            $viewObj = array(
                                'type' => "year",
                                'amount' => $amount->amounts,
                                'month' => substr($amount->month, 0, 3),
                            );
                        } else {
                            $month_name = date("F", mktime(0, 0, 0, $rowno, 10));
                            $month_name = substr($month_name, 0, 3);
                            $viewObj = array(
                                'type' => "year",
                                'amount' => 0,
                                'month' => $month_name,
                            );
                        }
                        array_push($data, $viewObj);
                        $rowno++;
                    }

                    $rowno = $week->format('m');
                    $rowno++;
                }


                return response()->json([
                    'status' => true,
                    'data' => $data,
                    'message' => ""
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => "",
                    'message' => "Something went wrong"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => "",
                'message' => $e->getMessage()
            ]);
        }
    }

}
