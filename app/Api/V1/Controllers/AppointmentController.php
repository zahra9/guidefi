<?php

namespace App\Api\V1\Controllers;

use App\AdvisorClientAppointment;
use App\Api\V1\Helpers\Utility;
use App\Http\Controllers\Controller;
use App\Question;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class AppointmentController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getApptTiming(Request $request)
    {
        try {
            $user = Auth::user();
            $canStartCAll = false;
            if($user->isAdvisor()){
                $appointment = AdvisorClientAppointment::with('client')->where('client_id', $request->get('user'))->where('advisor_id', $user->id)->where('appointment_status', 'Booked')->first();
            }elseif ($user->isClient()){
                $appointment = AdvisorClientAppointment::with('advisor')->where('advisor_id', $request->get('user'))->where('client_id', $user->id)->where('appointment_status', 'Booked')->first();
            }else{
                return response()->json([
                    'status' => false,
                    'data'=> [],
                    'message' => 'Not a valid request'
                ]);
            }
            $utilityClass = new Utility();

            if(!$appointment){
                return response()->json([
                    'status' => false,
                    'data'=> [],
                    'message' => 'No appointment found.'
                ]);
            }

            if($appointment){
                if ($appointment && Carbon::parse($appointment->appointment_date . ' ' . $appointment->end_time)->lt(Carbon::now())){
                    if ($appointment && $appointment->call_status == 1) {
                        return response()->json([
                            'status' => false,
                            'data'=> [],
                            'message' => 'No appointment found.'
                        ]);
                    }
                }
            }
            if($appointment->client_timezone == $appointment->advisor_timezone) {


                if (!is_null(config('app.timezone')) && $appointment->client_timezone !== config('app.timezone')) {
                    $from = $appointment->client_timezone;
                    $to = config('app.timezone');
                    $datetyme = Carbon::parse($appointment->appointment_date.' '.$appointment->start_time);
                    $response = $utilityClass->convertTimeZone($datetyme, $from, $to);
                    $date = date('Y-m-d', strtotime($response));
                    $day = Carbon::parse($response)->format('l');
                    $start_time = date('H:i', strtotime($response));
                    $end_time = Carbon::parse($start_time)->addMinutes(30)->format('H:i');
                    $datetime = date('Y-m-d H:i:s', strtotime($response));
                }

                // if the time is in new day we need to handle this.

                if($end_time == '00:00'){
                    $end_date = Carbon::parse($date)->addDay()->format('Y-m-d');
                }else{
                    $end_date = $date;
                }

                if (Carbon::now()->gt(Carbon::parse($date.' '. $start_time)) && Carbon::now()->lt(Carbon::parse($end_date.' '.$end_time))) {
                    $canStartCAll = true;
                }

            }else{


                if (!is_null(config('app.timezone')) && $appointment->client_timezone !== config('app.timezone')) {
                    $from = $appointment->client_timezone;
                    $to = config('app.timezone');
                    $datetyme = Carbon::parse($appointment->appointment_date.' '.$appointment->start_time);
                    $response = $utilityClass->convertTimeZone($datetyme, $from, $to);
                    $client_date = date('Y-m-d', strtotime($response));
                    $day = Carbon::parse($response)->format('l');
                    $client_appt_start_time = date('H:i', strtotime($response));
                    $client_appt_end_time = Carbon::parse($client_appt_start_time)->addMinutes(30)->format('H:i');
                    $datetime = date('Y-m-d H:i:s', strtotime($response));
                }

                if (!is_null(config('app.timezone')) && $appointment->advisor_timezone !== config('app.timezone')) {
                    $from = $appointment->advisor_timezone;
                    $to = config('app.timezone');
                    $datetyme = Carbon::parse($appointment->appointment_date.' '.$appointment->start_time);
                    $response = $utilityClass->convertTimeZone($datetyme, $from, $to);
                    $advisor_date = date('Y-m-d', strtotime($response));
                    $day = Carbon::parse($response)->format('l');
                    $advisor_appt_start_time = date('H:i', strtotime($response));
                    $advisor_appt_end_time = Carbon::parse($advisor_appt_start_time)->addMinutes(30)->format('H:i');
                    $datetime = date('Y-m-d H:i:s', strtotime($response));
                }

                if (Carbon::now()->gt(Carbon::parse($client_date.' '.$client_appt_start_time)) && Carbon::now()->lt(Carbon::parse($client_date .' '.$client_appt_start_time))) {
                    if (Carbon::now()->gt(Carbon::parse($advisor_date.' '.$advisor_appt_start_time)) && Carbon::now()->lt(Carbon::parse($advisor_date.' '.$advisor_appt_start_time))) {
                        $canStartCAll = true;
                    }
                }

            }
            $appointment['can_start_call'] = $canStartCAll;

            return response()->json([
                'status' => true,
                'data'=> $appointment,
                'message' => 'Timings are retrived.'
            ]);
        }catch (ErrorException $exception){
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return Response
     */
    public function show(Question $question)
    {
        //
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
