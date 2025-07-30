<?php

namespace App\Api\V1\Controllers;

use App\AdvisorClientAppointment;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\TherapistTransformer;
use App\ClientAdvisorDeals;
use App\Jobs\SendWelcomeEmailToClientJob;
use App\MatchedAdvisorList;
use App\Person;
use App\RatesCardOffers;
use App\Therapist;
use App\User;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use League\Fractal\Resource\Collection;
use App\Api\V1\Transformers\UserTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function index()
    {

        $fractal = new Manager();

        $fractal->parseIncludes(Input::get('include', ''));

        $limit = Input::get('limit', 10);

        $queryParams = array_diff_key($_GET, array_flip(['page']));
        $usersPaginator = User::paginate($limit)
            ->appends($queryParams)
            ->appends([
                'limit' => $limit,
            ]);

        $users = new Collection($usersPaginator->items(), new UserTransformer());
        $users->setPaginator(new IlluminatePaginatorAdapter($usersPaginator));

        return $fractal->createData($users)->toArray();
    }

    public function getProfile(Request $request)
    {
        try {
            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $user = Auth::user();

            if ($request->has('user_id') && $request->user_id) {
                $id = $request->user_id;
            } else {
                $id = $user->id;
            }

            $client = User::with('clientAppointment')->with('clientRequest')->where('id', $id)->first();


            if ($client->isAdvisor()) {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'You are advisor. Try with a client.'
                ]);
            }

            $records = new Item($client, new UserTransformer());
            $manager = $manager->createData($records);

            $callLog = null;

            // get a user active appointment.
            // then find a call on the bases of that.

            $returnRec = $manager->toArray();

            if ($user->isAdvisor()) {
                $appointment = AdvisorClientAppointment::where('client_id', $id)->where('advisor_id', $user->id)->where('appointment_status', 'Booked')->first();
                $returnRec['discovery_call_done'] = 0;
                if ($appointment && Carbon::parse($appointment->appointment_date . ' ' . $appointment->end_time)->lt(Carbon::now())){
                    if ($appointment && $appointment->call_status == 0) {
                        $returnRec['discovery_call_done'] = 0;
                    }else if($appointment && $appointment->call_status == 1){
                        $callLog = $client->clientCallLogs()->where('appointment_id', $appointment->id)->where('advisor_id', $user->id)->first();
                        $returnRec['discovery_call_done'] = !is_null($callLog) ? 1 : 0;
                    }
                }

                $alreadyMatched = MatchedAdvisorList::where('advisor_id', $user->id)->where('client_id', $id)->latest('id')->first();
                if ($alreadyMatched) {
                    if ($alreadyMatched->client_request_status == 2 && $alreadyMatched->advisor_request_status == 1) {
                        $returnRec['status'] = 'offered';
                    }else{
                        $returnRec['status'] = 'not-offered';
                    }
                } else if ($alreadyMatched->client_request_status == 4 && $alreadyMatched->advisor_request_status == 4) {
                    $returnRec['status'] = 'contract_active';
                } else if ($alreadyMatched->client_request_status == 5 && $alreadyMatched->advisor_request_status == 5) {
                    $returnRec['status'] = 'contract_ended';
                }

                $contractDetails = ClientAdvisorDeals::with('match')->with('advisor')->with('client')->where('client_id', $id)->where('advisor_id', $user->id)->first();

                $contract = null;
                if ($contractDetails && $contractDetails->offer) {

                    if ($contractDetails->offer->ratecard) {

                        $answers = $contractDetails->offer->ratecard->answers;
                        $servicesArray = $answers->filter(function ($query) {
                            return $query->question_text == 'Please indicate the financial product/services that your firm provides:';
                        });

                        $getServices = implode(', ', $servicesArray->pluck('answer_text')->toArray());

                        $returnRec['contract_status'] = $contractDetails->status ?  'contract_active' : 'contract_ended';

                        $contract = [
                            "id" => $contractDetails->offer->id,
                            "offer_id" => $contractDetails->offer->id,
                            "appointment_id" => $contractDetails->appointment_advisor_client_id,
                            "topic" => $getServices,
                            "start_date" => $contractDetails->deal_start_date,
                            "end_date" => $returnRec['contract_status'] == 'contract_ended' ?  $contractDetails->deal_end_date : null,
                            "contract_renew_time" => $contractDetails->offer->end_time,
                            "status" => $contractDetails->offer->status,
                            "offer_title" => $getServices,
                            "price" => '$'.number_format($contractDetails->offer->amount, 2),
                            "price_tag" => $contractDetails->offer->price_tag
                        ];
                    }

                }else{
                    $returnRec['contract_status'] = 'contract_ended';
                }

                if($appointment){
                    if (Carbon::parse($appointment->appointment_date . ' ' . $appointment->end_time)->lt(Carbon::now()))
                        $is_passed = 1;
                    else
                        $is_passed = 0;
                }else{
                    $is_passed = 0;
                }

                $returnRec['appointment'] = $is_passed ? null : $appointment;
                $returnRec['contract'] = $contract;
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
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function updateAvailability(Request $request)
    {
        try {
            $user = Auth::user();

            if (!is_null($user->therapist)) {
                $this->validate($request, [
                    'appointment_availability' => 'required'
                ]);

                $user->therapist()->update($request->only('appointment_availability'));
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Availability updated successfully!'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Availability can be updated by Therapist only'
                ]);

            }
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong! Please, try again later.'
            ]);
        }
    }

    public function resendActivation(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Something went wrong!',
                ], 200);
            }
            SendWelcomeEmailToClientJob::dispatch($user)->delay(now()->addMinutes(1));
            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Please check your email for an activation link.',
            ], 200);

        } catch (ErrorException $exception) {
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong! Please try again later.'
            ], 200);
        }
    }

    public function updateProfile(Request $request)
    {

        try {
            $user = Auth::user();


            if ($user->isClient()) {

                if ($request->has('image') && $request->get('image') != '') {
                    $personInput['image_url'] = $request->get('image');
                    $user->person->update($personInput);
                }else{
                    $this->validate($request, [
                        'name' => 'required',
                        'gender' => 'required',
                        'age' => 'required',
                        'income_level' => 'required',
                        'investment_level' => 'required',
                        'assets' => 'required'
                    ]);

                    $requestInputs = $request->only('income_level', 'investment_level', 'assets');
                    $personInput = $request->only('gender', 'age');
                    $userInput = $request->only('name');
                    $name = explode(' ', $request->get('name'));
                    $personInput['first_name'] = $name[0];
                    $personInput['last_name'] = $name[1];

                    if ($request->has('image') && $request->get('image') != '') $personInput['image_url'] = $request->get('image');


                    $user->update($userInput);
                    $user->clientrequest->update($requestInputs);
                    $user->person->update($personInput);
                }
            } else {

                if ($request->has('image') && $request->get('image') != '') {
                    $personInput['image_url'] = $request->get('image');
                    $user->person->update($personInput);
                }
                if($request->has('name') && $request->get('name')){
                    $userInput['name'] = $request->get('name');
                    $user->update($userInput);
                }
            }

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Profile updated successfully!'
            ]);
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }

    }

    public function updateFirstLoginPassword(Request $request)
    {

        try {
            $user = Auth::user();
            if ($user->first_login) {
                $user->password = $request->get('password');
                $user->first_login = 0;
                $user->save();

                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Your password has been successfully updated!',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'You already updated your password! try in update profile now.',
                ], 200);
            }
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something went wrong, Please try again later!',
            ], 200);
        }

    }

    public function updateUserImage(Request $request)
    {

        try {
            $user = Auth::user();
            $bbImageKey = "dc791ba1ef8c9e99745fa708784ab99b";

            $image = $request->get('image');

            $payload = ['image' => $image];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key=' . $bbImageKey);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $content = curl_exec($ch);
            $data = json_decode($content, true);


            $userInput['image_url'] = $data['data']['display_url'];

            if ($user->therapist) {
                $user->therapist()
                    ->update(['image_url' => $data['data']['display_url']
                    ]);
            }
            $user->update($userInput);

            curl_close($ch);

            return response()->json([
                'status' => true,
                'data' => $user,
                'message' => 'Profile picture updated successfully'
            ]);

        } catch (ErrorException $exception) {
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong! Please, try again later.'
            ]);
        }

    }

    public function removeUserImage(Request $request)
    {

        try {
            $user = Auth::user();

            $userInput['image_url'] = 'https://i.ibb.co/D4Y57Ts/iconfinder-photo-370076.png';

            if ($user->therapist) {
                $user->therapist()
                    ->update(['image_url' => $userInput['image_url']
                    ]);
            }

            $user->update($userInput);

            return response()->json([
                'status' => true,
                'data' => $user,
                'message' => 'Profile picture removed successfully!'
            ]);

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong! Please, try again later.'
            ]);
        }

    }


    // *****term and condition

    public function termAndCondition(Request $request)
    {
        try {
            $status = $request->get('status');
            $user = Auth::user();
            if(!is_null($user->person)){
                $user->person->update(['term_condition'=>$status]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Term And Condition Successfully Saved!'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'data' => null,
            ]);
        }
    }

}
