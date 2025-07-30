<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Helpers\FcmController;
use App\Api\V1\Helpers\Utility;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\TherapistTransformer;
use App\Appointment;
use App\ClientSelectedTherapist;
use App\Http\Controllers\Controller;
use App\Question;
use App\Repositories\EmailManager;
use App\SubscriptionPlan;
use App\Therapist;
use App\TherapistAvailablity;
use App\TherapistClientAppointment;
use App\Therapistrequest;
use App\TimeSlots;
use App\TimeSlotsFormat;
use App\Timing;
use App\User;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\PDOConnection;
use ErrorException;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Subscription;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;


class TherapistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        try {

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $user = Auth::user();

            //connect to database
//            $connection = mysqli_connect("ayana-mariadb.cuprhov6etff.us-west-1.rds.amazonaws.com", "ayana_admin", "WelcomeAyana2020!@", "ayana_prod", "3306");
//            $connection = mysqli_connect("api.ayanatherapy.com", "apiaya5_remote", "Welcome2019@", "apiaya5_ayana_live", "3306");
            $connection = mysqli_connect(env('DB_HOST', 'ayana-mariadb.cuprhov6etff.us-west-1.rds.amazonaws.com'), env('DB_USERNAME', 'ayana_admin'), env('DB_PASSWORD', 'WelcomeAyana2020!@'), env('DB_DATABASE', 'ayana_prod'), env('DB_PORT', '3306'));
            $result = mysqli_query($connection, "CALL getTherapistListLatest($user->id)");
            $ids = [];
            $tmpData = [];
            if ($connection->affected_rows == 0) {
                $ids = [];
                $tmpData = [];
            } else {
                while ($row = mysqli_fetch_array($result)) {
//                    $ids[] = $row[0];
                    $therapist = Therapist::with('ratings')->where('id', $row['id'])->first();

                    $timings = $therapist->timings;
                    $tmp = [];
                    if (!is_null($timings) && $timings) {
                        $days = [];
                        foreach ($timings as $key => $timing) {
                            if (!in_array($timing->day, $days)) {
                                $start_time = TherapistAvailablity::where('therapist_id', $timing->therapist_id)->where('day', $timing->day)->orderBy('start_time', 'asc')->first();
                                $end_time = TherapistAvailablity::where('therapist_id', $timing->therapist_id)->where('day', $timing->day)->orderBy('end_time', 'desc')->first();
                                $tmp[] = [
                                    'id' => $timing->id,
                                    'therapist_id' => $timing->therapist_id,
                                    'day' => $timing->day,
                                    'start_time' => $start_time->start_time,
                                    'end_time' => $end_time->end_time,
                                    'time_zone' => $timing->time_zone,
                                    'created_at' => Carbon::parse($timing->created_at)->format('m/d/Y h:i A'),
                                    'updated_at' => Carbon::parse($timing->created_at)->format('m/d/Y h:i A')
                                ];
                                $days[] = $timing->day;
                            }
                        }
                    }

                    $tmpData[] = [
                        'id' => intval($row['id']),
                        'user_id' => intval($row['user_id']),
                        'name' => utf8_encode($row['name']),
                        'email' => utf8_encode($row['email']),
                        'image_url' => utf8_encode($row['image_url']),
                        'activated' => utf8_encode($row['activated']),
                        'about' => utf8_encode($row['about']),
                        'availability' => utf8_encode($row['availability']),
                        'isFilledQuestion' => utf8_encode($row['is_questionare_completed']),
                        'certificatelist' => utf8_encode($row['certificatelist']),
                        'experience' => utf8_encode($row['experience']),
                        'rating' => !is_null($therapist) ? $therapist->ratings()->sum('ratings') > 0 ? $therapist->ratings()->sum('ratings') / $therapist->ratings()->count() : 0 : 0,
                        'timing' => $tmp,
                    ];

                }
            }

            /*$therapists = Therapist::whereHas('user',function ($q) use($ids){
                $q->whereIn('id',$ids);
            })->get();
            $therapists = new Collection($therapists, new TherapistTransformer());

            $manager = $manager->createData($therapists);*/

            return response()->json([
                'status' => true,
                'data' => $tmpData,
                'message' => 'Matched Therapist successfully retrieved!'
            ]);
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong, Please try again later!'
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getTherapistAppointments(Request $request)
    {
        try {

            $utilityClass = new Utility();
            $date = Carbon::now();
            /*if(!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')){
                $from = $request->get('timezone');
                $to = config('app.timezone');
                $response = $utilityClass->convertTimeZone($date, $from, $to);
                $date = date('Y-m-d', strtotime($response));
                $datetime = date('Y-m-d H:i:s', strtotime($response));
            }*/

            $todayAppointments = TherapistClientAppointment::where('therapist_id', Auth::user()->therapist->id)->with('user')->where('appointment_status', 'Booked')->whereDate('appointment_date', $date)->orderBy('appointment_date', 'ASC')->get();
            $futureAppointments = TherapistClientAppointment::where('therapist_id', Auth::user()->therapist->id)->with('user')->where('appointment_status', 'Booked')->whereDate('appointment_date', '>', $date)->orderBy('appointment_date', 'ASC')->get();

            if (count($todayAppointments) > 0 || count($futureAppointments) > 0) {
                $resultToday = [];
                $resultFuture = [];
                $utilityClass = new Utility();

                foreach ($futureAppointments as $key => $appointment) {

                    $issametimezone = 1;
                    if (!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')) {

                        $inputTime = $appointment->appointment_date . ' ' . $appointment->start_time;
                        $from = config('app.timezone');
                        $to = $request->get('timezone');
                        $response = $utilityClass->convertTimeZone($inputTime, $from, $to);
                        $datetime = date('Y-m-d H:i:s', strtotime($response));
                        $issametimezone = 0;
                    }

                    $resultFuture[] = [
                        "id" => $appointment->id,
                        "therapist_id" => $appointment->therapist_id,
                        "user_id" => $appointment->client_id,
                        "datetime" => $issametimezone === 0 ? $datetime : Carbon::parse($appointment->appointment_date . ' ' . $appointment->start_time)->format('Y-m-d H:i:s'),
                        "datetime_formated" => Carbon::parse($issametimezone === 0 ? $datetime : $appointment->appointment_date . ' ' . $appointment->start_time)->format('m/d/Y h:i A'),
                        "timeslot_number" => $appointment->timeslot_number,
//                        "is_archive"=> $appointment->is_archive,
                        "type" => $appointment->type,
//                        "reason"=> $appointment->reason,
                        "created_at" => $appointment->created_at,
                        "updated_at" => $appointment->updated_at,
                        "appointment_number" => $appointment->appointment_number,
//                        "other_number"=> $appointment->other_number,
                        "deleted_at" => $appointment->deleted_at,
                        "user" => $appointment->user,
                    ];
                }
                foreach ($todayAppointments as $key => $appointment) {

                    $issametimezone = 1;
                    if (!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')) {

                        $inputTime = $appointment->appointment_date . ' ' . $appointment->start_time;
                        $from = config('app.timezone');
                        $to = $request->get('timezone');
                        $response = $utilityClass->convertTimeZone($inputTime, $from, $to);
                        $datetime = date('Y-m-d H:i:s', strtotime($response));
                        $issametimezone = 0;
                    }

                    $resultToday[] = [
                        "id" => $appointment->id,
                        "therapist_id" => $appointment->therapist_id,
                        "user_id" => $appointment->client_id,
                        "datetime" => $issametimezone === 0 ? $datetime : Carbon::parse($appointment->appointment_date . ' ' . $appointment->start_time)->format('Y-m-d H:i:s'),
                        "datetime_formated" => Carbon::parse($issametimezone === 0 ? $datetime : $appointment->appointment_date . ' ' . $appointment->start_time)->format('m/d/Y h:i A'),
                        "timeslot_number" => $appointment->timeslot_number,
//                        "is_archive"=> $appointment->is_archive,
                        "type" => $appointment->type,
//                        "reason"=> $appointment->reason,
                        "created_at" => $appointment->created_at,
                        "updated_at" => $appointment->updated_at,
                        "appointment_number" => $appointment->appointment_number,
//                        "other_number"=> $appointment->other_number,
                        "deleted_at" => $appointment->deleted_at,
                        "user" => $appointment->user,
                    ];
                }

                return response()->json([
                    'status' => true,
                    'data' => ['today' => $resultToday, 'future' => $resultFuture],
                    'message' => count($resultToday) + count($resultFuture) . ' Appointment(s) found.'
                ]);

                //notify for appointment booking
                if($appointment->client){
                    $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                    $description = 'Notify Email to view advisor discovery call status';
                    $fcmController->pushNotification('Appointment Booked', $description, 'appointment', $appointment->client_id);

                }


            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No appointments found.'
                ]);
            }

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getClientAppointments(Request $request)
    {


        try {
//            $todayAppointments = Appointment::where('user_id', Auth::user()->id)->with('therapist')->whereDate('datetime', Carbon::now())->orderBy('datetime', 'ASC')->get();
//            $futureAppointments = Appointment::where('user_id', Auth::user()->id)->with('therapist')->whereDate('datetime', '>', Carbon::now())->orderBy('datetime', 'ASC')->get();
//            $pastAppointments = Appointment::where('user_id', Auth::user()->id)->with('therapist')->whereDate('datetime', '<', Carbon::now())->orderBy('datetime', 'ASC')->get();
//            $canceledAppointments = Appointment::onlyTrashed()->where('user_id', Auth::user()->id)->with('therapist')->whereDate('datetime', '<', Carbon::now())->orderBy('datetime', 'ASC')->get();
            $utilityClass = new Utility();
            $date = Carbon::now();
            /*if(!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')){
                $from = config('app.timezone');
                $to = $request->get('timezone');
                $response = $utilityClass->convertTimeZone($date, $from, $to);
                $date = date('Y-m-d', strtotime($response));
                $datetime = date('Y-m-d H:i:s', strtotime($response));
            }*/

            $todayAppointments = TherapistClientAppointment::where('client_id', Auth::user()->id)->with('therapist')->where('appointment_status', 'Booked')->whereDate('appointment_date', $date)->orderBy('appointment_date', 'ASC')->get();
            $futureAppointments = TherapistClientAppointment::where('client_id', Auth::user()->id)->with('therapist')->where('appointment_status', 'Booked')->whereDate('appointment_date', '>', $date)->orderBy('appointment_date', 'ASC')->get();
            $pastAppointments = TherapistClientAppointment::where('client_id', Auth::user()->id)->with('therapist')->where('appointment_status', 'Booked')->whereDate('appointment_date', '<', $date)->orderBy('appointment_date', 'ASC')->get();
            $canceledAppointments = TherapistClientAppointment::where('client_id', Auth::user()->id)->with('therapist')->where('appointment_status', 'Cancelled')->orderBy('appointment_date', 'ASC')->get();

            $resultToday = [];
            $resultFuture = [];
            $resultPast = [];
            $resultCanceled = [];

            foreach ($futureAppointments as $key => $appointment) {

                $issametimezone = 1;
                if (!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')) {

                    $inputTime = $appointment->appointment_date . ' ' . $appointment->start_time;
                    $from = config('app.timezone');
                    $to = $request->get('timezone');
                    $response = $utilityClass->convertTimeZone($inputTime, $from, $to);
                    $datetime = date('Y-m-d H:i:s', strtotime($response));
                    $issametimezone = 0;
                }

                $resultFuture[] = [
                    "id" => $appointment->id,
                    "therapist_id" => $appointment->therapist_id,
                    "user_id" => $appointment->client_id,
                    "datetime" => $issametimezone === 0 ? $datetime : Carbon::parse($appointment->appointment_date . ' ' . $appointment->start_time)->format('Y-m-d H:i:s'),
                    "datetime_formated" => Carbon::parse($issametimezone === 0 ? $datetime : $appointment->appointment_date . ' ' . $appointment->start_time)->format('m/d/Y h:i A'),
                    "timeslot_number" => $appointment->timeslot_number,
//                                "is_archive"=> $appointment->is_archive,
                    "type" => $appointment->type,
//                                "reason"=> $appointment->reason,
                    "created_at" => $appointment->created_at,
                    "updated_at" => $appointment->updated_at,
                    "appointment_number" => $appointment->appointment_number,
//                                "other_number"=> $appointment->other_number,
                    "deleted_at" => $appointment->deleted_at,
                    "therapist" => $appointment->therapist,
                ];
            }
            foreach ($todayAppointments as $key => $appointment) {

                $issametimezone = 1;
                if (!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')) {

                    $inputTime = $appointment->appointment_date . ' ' . $appointment->start_time;
                    $from = config('app.timezone');
                    $to = $request->get('timezone');
                    $response = $utilityClass->convertTimeZone($inputTime, $from, $to);
                    $datetime = date('Y-m-d H:i:s', strtotime($response));
                    $issametimezone = 0;
                }

                $resultToday[] = [
                    "id" => $appointment->id,
                    "therapist_id" => $appointment->therapist_id,
                    "user_id" => $appointment->client_id,
                    "datetime" => $issametimezone === 0 ? $datetime : Carbon::parse($appointment->appointment_date . ' ' . $appointment->start_time)->format('Y-m-d H:i:s'),
                    "datetime_formated" => Carbon::parse($issametimezone === 0 ? $datetime : $appointment->appointment_date . ' ' . $appointment->start_time)->format('m/d/Y h:i A'),
                    "timeslot_number" => $appointment->timeslot_number,
//                        "is_archive"=> $appointment->is_archive,
                    "type" => $appointment->type,
//                        "reason"=> $appointment->reason,
                    "created_at" => $appointment->created_at,
                    "updated_at" => $appointment->updated_at,
                    "appointment_number" => $appointment->appointment_number,
//                        "other_number"=> $appointment->other_number,
                    "deleted_at" => $appointment->deleted_at,
                    "therapist" => $appointment->therapist,
                ];
            }
            foreach ($pastAppointments as $key => $appointment) {

                $issametimezone = 1;
                if (!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')) {

                    $inputTime = $appointment->appointment_date . ' ' . $appointment->start_time;
                    $from = $request->get('timezone');
                    $to = config('app.timezone');
                    $response = $utilityClass->convertTimeZone($inputTime, $from, $to);
                    $datetime = date('Y-m-d H:i:s', strtotime($response));
                    $issametimezone = 0;
                }

                $resultPast[] = [
                    "id" => $appointment->id,
                    "therapist_id" => $appointment->therapist_id,
                    "user_id" => $appointment->client_id,
                    "datetime" => $issametimezone === 0 ? $datetime : Carbon::parse($appointment->appointment_date . ' ' . $appointment->start_time)->format('Y-m-d H:i:s'),
                    "datetime_formated" => Carbon::parse($issametimezone === 0 ? $datetime : $appointment->appointment_date . ' ' . $appointment->start_time)->format('m/d/Y h:i A'),
                    "timeslot_number" => $appointment->timeslot_number,
//                        "is_archive"=> $appointment->is_archive,
                    "type" => $appointment->type,
//                        "reason"=> $appointment->reason,
                    "created_at" => $appointment->created_at,
                    "updated_at" => $appointment->updated_at,
                    "appointment_number" => $appointment->appointment_number,
//                        "other_number"=> $appointment->other_number,
                    "deleted_at" => $appointment->deleted_at,
                    "therapist" => $appointment->therapist,
                ];
            }
            foreach ($canceledAppointments as $key => $appointment) {

                $issametimezone = 1;
                if (!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')) {

                    $inputTime = $appointment->appointment_date . ' ' . $appointment->start_time;
                    $from = $request->get('timezone');
                    $to = config('app.timezone');
                    $response = $utilityClass->convertTimeZone($inputTime, $from, $to);
                    $datetime = date('Y-m-d H:i:s', strtotime($response));
                    $issametimezone = 0;
                }

                $resultCanceled[] = [
                    "id" => $appointment->id,
                    "therapist_id" => $appointment->therapist_id,
                    "user_id" => $appointment->client_id,
                    "datetime" => $issametimezone === 0 ? $datetime : Carbon::parse($appointment->appointment_date . ' ' . $appointment->start_time)->format('Y-m-d H:i:s'),
                    "datetime_formated" => Carbon::parse($issametimezone === 0 ? $datetime : $appointment->appointment_date . ' ' . $appointment->start_time)->format('m/d/Y h:i A'),
                    "timeslot_number" => $appointment->timeslot_number,
//                        "is_archive"=> $appointment->is_archive,
                    "type" => $appointment->type,
//                        "reason"=> $appointment->reason,
                    "created_at" => $appointment->created_at,
                    "updated_at" => $appointment->updated_at,
                    "appointment_number" => $appointment->appointment_number,
//                        "other_number"=> $appointment->other_number,
                    "deleted_at" => $appointment->deleted_at,
                    "therapist" => $appointment->therapist,
                ];
            }

            if (count($resultToday) + count($resultFuture) + count($pastAppointments) + count($resultCanceled) < 1) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No Appointment found.'
                ]);
            }
            return response()->json([
                'status' => true,
                'data' => ['today' => $resultToday, 'future' => $resultFuture, 'past' => $resultPast, 'canceled' => $resultCanceled],
                'message' => count($resultToday) + count($resultFuture) + count($pastAppointments) . +count($resultCanceled) . ' Appointment(s) found.'
            ]);

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

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

    public function get_therapist_availability(Request $request)
    {
        try {

            $this->validate($request, [
                'therapist_id' => 'required|exists:therapists,id'
            ]);

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $therapist = Therapist::find($request->get('therapist_id'));

            if (!is_null($therapist) && !is_null($therapist->timing)) {
                $therapist = $therapist->timing;
                $therapist = new Collection($therapist, new TherapistTransformer());

                $manager = $manager->createData($therapist);
                return response()->json([
                    'status' => true,
                    'data' => $manager->toArray(),
                    'message' => ''
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Therapist or timing not found!'
                ]);
            }
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    public function getTimeSlot(Request $request)
    {
        try {

            $this->validate($request, [
                'date' => 'required',
                'timezone' => 'required',
                'therapist_id' => 'required|exists:therapists,id'
            ]);

            $date = Carbon::parse($request->get('date'))->format('Y-m-d');
            $day = Carbon::parse($date)->format('l');
            $therapist = Therapist::find($request->get('therapist_id'));

            $timings = TherapistAvailablity::where('therapist_id', $request->get('therapist_id'))->where('day', $day)->get();
            if (!$timings) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Therapist not available on this ' . $day
                ]);
            }
            $timeslots = [];
            $utilityClass = new Utility();

            foreach ($timings as $key => $value) {
                $date = Carbon::parse($request->get('date'))->format('Y-m-d');
                $slot_start_time = $value->start_time;
                $timezone = $value->time_zone;
                $slot_end_time = $value->end_time;

                /*if(!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')){
                    $time = $date . ' ' . $slot_start_time;
                    $from = config('app.timezone');
                    $to = $request->get('timezone');
                    $time = $utilityClass->convertTimeZone($time, $from, $to);
                    $date = date("Y-m-d",strtotime($time));
                    $slot_start_time = date("H:i",strtotime($time));
                    $slot_end_time = Carbon::parse($slot_start_time)->addMinutes(30)->format("H:i");
                }*/

                DB::enableQueryLog();
//                $booked_timeslots = $therapist->TherapistClientAppointment()->where('start_time', $slot_start_time)->where('end_time', $slot_end_time)->whereDate('appointment_date','like',$date)->first();
                $booked_timeslots = $therapist->TherapistClientAppointment()->where('appointment_status', 'Booked')->where('timeslot_number', $value->id)->whereDate('appointment_date', 'like', $date)->first();
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
                    'label' => Carbon::parse($slot_start_time)->format("H:i") . ' - ' . Carbon::parse($slot_end_time)->format("H:i"), // updated start time.
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
                return $this->sendResponse([], 'Therapist is not available.');
            }
        } Catch (Exception $e) {
            error_log($e);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'There is something wrong, please try again.'
            ]);
        }
    }

    public function testNotification(Request $request)
    {
        $fcmController = new FcmController(env('FCM_SERVER_KEY'));
        $response = $fcmController->pushNotification($request->title, $request->description, $request->type, $request->user_id);
        return $this->sendResponse($response, 'New Notification sent.');
    }

    public function changeAppointmentType(Request $request)
    {
        try {
            $appointment = TherapistClientAppointment::where('id', $request->get('appointment_id'))->first();
            if ($appointment) {
                $appointment->type = $request->get('type');
                $update = $appointment->save();

                if ($update) {
                    $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                    $description = $appointment->user->name . ' ' . ' has Changed the appointment type to ' . $request->get('type') . ' the Appointment is booked on ' . Carbon::parse($appointment->appointment_date)->format('m/d/Y') . ' at ' . Carbon::parse($appointment->appointment_date)->format('h:i A');
                    $fcmController->pushNotification('Appointment Type Changed', $description, 'appointment', $appointment->therapist->user_id);

                    return $this->sendResponse([], 'Updated the Appointment type');
                } else return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'There is something wrong, please try again.'
                ]);
            } else {
                return $this->sendResponse([], 'No Active Appointment found.');
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'There is something wrong, please try again.'
            ]);
        }

    }

    public function cancelAppointment(Request $request)
    {
        try {
            $appointment = TherapistClientAppointment::where('id', $request->get('appointment_id'))->first();

            $datetime = $appointment->appointment_date;
            if (!is_null(config('app.timezone')) && !is_null($appointment->therapist_timezone) && $appointment->therapist_timezone !== config('app.timezone')) {
                $utilityClass = new Utility();
                $time = Carbon::parse($appointment->appointment_date);

                $from = config('app.timezone');
                $to = $appointment->therapist_timezone;
                $time = $utilityClass->convertTimeZone($time, $from, $to);
                $datetime = Carbon::parse($time)->format('m/d/Y h:i A');
            }

            if ($appointment) {
                $appointment->appointment_status = 'Cancelled';
                $updated = $appointment->save();
//                $delete =  $appointment->delete();

                if ($updated) {
                    $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                    $description = $appointment->user->name . ' ' . ' has canceled an appointment on ' . Carbon::parse($datetime)->format('m/d/Y') . ' at ' . Carbon::parse($datetime)->format('h:i A');
                    $fcmController->pushNotification('Appointment Canceled', $description, 'appointment', $appointment->therapist->user_id);

                    return $this->sendResponse([], 'Appointment Canceled');
                } else return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'There is something wrong, please try again.'
                ]);
            } else {
                return $this->sendResponse([], 'No Active Appointment found.');
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'There is something wrong, please try again.'
            ]);
        }

    }

    public function save_appointment(Request $request)
    {
        try {
            $user = Auth::user();
            $therapist = Therapist::find($request->get('therapist_id'));
            $input = $request->all();

            $utilityClass = new Utility();
            $start_time = $request->has('start_time') ? $input['start_time'] : $input['time'];
            $end_time = $request->has('end_time') ? $input['end_time'] : Carbon::parse($start_time)->addMinutes(30)->format('H:i');
            $date = ($request->has('appointment_date') ? $input['appointment_date'] : $input['date']) . ' ' . $start_time;
            $day = $request->has('appointment_day') ? $input['appointment_day'] : strtolower(Carbon::parse($date)->format('l'));
            $datetime = date('Y-m-d H:i:s', strtotime("$date $start_time"));

            if (!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')) {
                $from = $request->get('timezone');
                $to = config('app.timezone');
                $response = $utilityClass->convertTimeZone($date, $from, $to);
                $date = date('Y-m-d', strtotime($response));
                $day = Carbon::parse($response)->format('l');
                $start_time = date('H:i', strtotime($response));
                $end_time = Carbon::parse($start_time)->addMinutes(30)->format('H:i');
                $datetime = date('Y-m-d H:i:s', strtotime($response));
            }


            // This is the section of the code in which we will check that a user can only have the number of the appointments that his/her
            // package allow him
            $currentPlan = $user->subscribedPlans()->where('active', 1)->orderBy('id', 'DESC')->first();
            $appointmentLimit = 0;
            $appointmentType = 0;
            if ($currentPlan->voice_call_limit > 0) {
                $appointmentLimit = $currentPlan->voice_call_limit;
                $appointmentType = 'Audio';
            } elseif ($currentPlan->video_call_limit > 0) {
                $appointmentLimit = $currentPlan->video_call_limit;
                $appointmentType = 'Video';
            }

            /*if($appointmentType !== $input['type']){
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Your pakage type is '. $appointmentType .' and you are going to book a '. $input['type'] . ' appointment '
                ]);
            }*/

            $monthNr = Carbon::parse($date)->month;
            $currentMonthAppointments = TherapistClientAppointment::whereMonth('appointment_date', $monthNr)->where('appointment_status', 'Booked')->where('client_id', $user->id)->count();
//            $currentMonthAppointments = Appointment::whereMonth('datetime',  $monthNr)->where('user_id',$user->id)->count();
            if ($appointmentLimit > $currentMonthAppointments) {
                $appointments_count = TherapistClientAppointment::count();
                $appointments_count = sprintf("%04s", $appointments_count);

//                $timing = Timing::where('therapist_id', $request->get('therapist_id'))->first();
                $timing = TherapistAvailablity::where('therapist_id', $request->get('therapist_id'))->where('day', $day)->first();
                if (!$timing) {
                    return response()->json([
                        'status' => false,
                        'data' => null,
                        'message' => 'Therapist not available on this day'
                    ]);
                }
                $input['client_timezone'] = $request->get('timezone');
                $input['therapist_timezone'] = $timing->time_zone;
//                $input['therapist_timezone'] = $timing->timezone;

                /*if(!is_null(config('app.timezone')) && $request->get('timezone') !== config('app.timezone')){
                    $utilityClass = new Utility();
                    $time = $date . ' ' . $start_time;
                    $from = $request->get('timezone');
                    $to = config('app.timezone');
                    $time = $utilityClass->convertTimeZone($time, $from, $to);
                    $datetime = Carbon::parse($time)->format('m/d/Y H:i');
//                    $input['appointment_date'] = Carbon::parse($time)->format('Y-m-d H:i:s');
                    $date = Carbon::parse($time)->format('Y-m-d');
                    $start_time = Carbon::parse($time)->format('H:i');
                    $end_time = Carbon::parse($time)->addMinutes(30)->format('H:i');
                }*/


                $input['appointment_number'] = 'AYN' . $appointments_count;

                $input['appointment_date'] = $date;
                $input['appointment_day'] = $day;
                $input['start_time'] = $start_time;
                $input['end_time'] = $end_time;
                $input['appointment_status'] = 'Booked';
                $input['is_archive'] = 0;


                DB::transaction(function () use ($input, $user, $therapist) {
                    $appointment = TherapistClientAppointment::create($input);
                    $appointment->user()->associate($user)->save();
                    $appointment->therapist()->associate($therapist)->save();
                });


                if (!is_null(config('app.timezone')) && !is_null($timing->time_zone) && $timing->time_zone !== config('app.timezone')) {
                    $utilityClass = new Utility();
                    $time = $datetime;
                    $from = config('app.timezone');
                    $to = $timing->time_zone;
                    $time = $utilityClass->convertTimeZone($time, $from, $to);
                    $datetime = Carbon::parse($time)->format('m/d/Y h:i A');
                }
                if(!is_null($therapist->user)) {

                    //notify for appointment booking
                    $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                    $description = $user->name . ' has booked an appointment on ' . Carbon::parse($datetime)->format('m/d/Y') . ' at ' . Carbon::parse($datetime)->format('h:i A');
                    $fcmController->pushNotification('Appointment Booked', $description, 'appointment', $therapist->user_id);

                    // email for appointment booking
                    EmailManager::sendMailQueue('Appointment Booked', $description, $therapist->user->name, $therapist->user->email);
                }
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Appointment Booked'
                ]);

            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'You have reached your appointment limit for this month.'
                ]);
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'There is something wrong, please try again.'
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
            $TherapistAvailablity = TherapistAvailablity::where('day', strtolower($day))->where('therapist_id', $user->therapist->id)->first();
            if ($TherapistAvailablity) $available = TimeSlots::where('start_time', $TherapistAvailablity->start_time)->where('end_time', $TherapistAvailablity->end_time)->first();

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
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' =>  $e->getMessage()
            ]);
        }

    }

    public function savetimeSlots(Request $request)
    {

        try {
            $users = User::whereHas('roles', function ($q) {
                $q->where('slug', 'therapist');
            })->get();
            $index = 0;
            foreach ($users as $key => $user) {

                $therapistRequest = Therapistrequest::where('email', $user->email)
                    ->whereNull('therapist_id')
                    ->where('status', 'Approved')->first();

//                $user2 = User::find($user->id);
                if (!is_null($therapistRequest)) {
                    $index++;

                    if ($user->therapist) {
                        $therapistRequest->therapist_id = $user->therapist->id;
                        $therapistRequest->save();
                        $therapistRequests = Therapistrequest::where('email', $user->email)
                            ->whereNull('therapist_id')
                            ->where('id', '<>', $therapistRequest->id)
                            ->update(['status' => 'Pending', 'therapist_id' => 0]);
                    } else {
                        $therapistRequests = Therapistrequest::where('email', $user->email)
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

        } catch (Exception $e) {
            dd($e);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'There is something wrong, please try again.'
            ]);
        }

        dd($users);

        if ($request->has('appointment') && $request->get('appointment') == 1) {
            TherapistClientAppointment::query()->truncate();
            $oldAppointments = Appointment::withTrashed()->get();

            $recods = [];
            foreach ($oldAppointments as $key => $appointment) {
                $tmpData['therapist_id'] = $appointment->therapist_id;
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
            TherapistClientAppointment::insert($recods);
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
            $TherapistAvailablity = TherapistAvailablity::where('day', strtolower($day))->where('advisor_id', $user->therapist->id)->get();

            $available = TimeSlots::where('time_slot_template_id', $request->get('timeslot'))->get();

            $alreadyTakenDay = 0;
            if ($TherapistAvailablity) {
                $alreadyTakenDay = 1;
            }
            $timeSlots = [];
            if ($TherapistAvailablity) {
                foreach ($available as $key => $value) {

                    $value->isSelected = 0;
                    unset($value->deleted_at);
                    if ($alreadyTakenDay) {
                        $match = $TherapistAvailablity->filter(function ($filterVal, $key) use ($value) {
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
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' =>  $e->getMessage()
            ]);
        }

    }

    public function saveTherapistAvailability(Request $request)
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
                $tmpData['advisor_id'] = $user->therapist->id;
                $tmpData['start_time'] = Carbon::parse($value['start_time'])->format('H:i');
                $tmpData['end_time'] = Carbon::parse($value['end_time'])->format('H:i');

                $recods[] = $tmpData;
            }

            $TherapistAvailablity = TherapistAvailablity::where('day', strtolower($data['day']))->where('advisor_id', $user->therapist->id)->get();

            if ($TherapistAvailablity) {
                TherapistAvailablity::where('day', strtolower($data['day']))->where('advisor_id', $user->therapist->id)->delete();
            }

            $TherapistAvailablity = TherapistAvailablity::insert($recods);


            if ($TherapistAvailablity) {
                return $this->sendResponse($TherapistAvailablity, 'Created successfully.');

            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'There is something wrong, please try again.'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'There is something wrong, please try again.'
            ]);
        }

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
                $advisor_id = $user->therapist->id;
            }
            $TherapistAvailablity = TherapistAvailablity::where('day', strtolower($request->get('day')))->where('advisor_id', $advisor_id)->get();

            if ($TherapistAvailablity) {
                $TherapistAvailablity->filter(function ($value, $key) {
                    $value->start_time = Carbon::parse($value->start_time)->format('H:i');
                    $value->end_time = Carbon::parse($value->end_time)->format('H:i');
                    return $value->label = Carbon::parse($value->start_time)->format('H:i') . ' - ' . Carbon::parse($value->end_time)->format('H:i');
                });
                return $this->sendResponse($TherapistAvailablity, 'Retrived successfully.');

            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'There is something wrong, please try again.'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'There is something wrong, please try again.'
            ]);
        }

    }

    /*
     * This is a method that will cancel the subscription for the user for that month.
     * */

    public function cancelSubscription(Request $request)
    {
        try {

            Stripe::setApiKey();
            // Creating a customer - If you want to create customer uncomment below code.
            $user = Auth::user();

            $customer = Customer::all(["email" => $user->email]);
            $customer = isset($customer['data']) && count($customer['data']) > 0 ? $customer['data'][0] : null;

            if (is_null($customer)) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Customer not found. Please try with another email.'
                ]);
            }

            // Delete Old Subscriptions
            $oldSubscription = Subscription::all(['customer' => $customer['id']]);
            $oldSubscription = isset($oldSubscription['data']) && count($oldSubscription['data']) > 0 ? $oldSubscription['data'] : null;
            // ALL old subscriptions deleted!
            if (!is_null($oldSubscription)) {
                foreach ($oldSubscription as $subs) {
                    $subs->delete();
                }

                /*TherapistClientAppointment::
                where('client_id', $user->id)
                    ->where('appointment_status', 'Booked')
                    ->whereDate('appointment_date', '>=', Carbon::now())
                    ->whereDate('start_time', '>=', Carbon::now())
                    ->update(['appointment_status' => 'Canceled']);*/

                $user->subscribedPlans()->update(['active' => 0]);
                $user->selectedTherapists()->update(['end_date' => Carbon::now(), 'is_active' => 0]);

                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Subscription cancelled successfully.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No subscription found against your details.'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something went wrong, please try again later.'
            ]);
        }

    }


}
