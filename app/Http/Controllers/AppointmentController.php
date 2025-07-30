<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\ClientSelectedTherapist;
use App\Therapist;
use App\TherapistClientAppointment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use jeremykenedy\LaravelRoles\Models\Role;
use Validator;
use App\Therapistrequest;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        //
        if($request->input('filter')){
            $filter = $request->input('filter');
        }else{
            $filter = 'all';
        }
        $from = Carbon::parse('2019-01-01')->format('Y-m-d');
        $to = Carbon::parse('')->format('Y-m-d');

        if($request->input('from') || $request->input('to')){
            $filter = 'datefilter';
        }
        switch($filter){
            case 'all':
                $appoinments = TherapistClientAppointment::with('user', 'therapist')
                    ->get();
                break;
            case 'audio':
                $appoinments = TherapistClientAppointment::with('user', 'therapist')
                    ->where('type', $filter)
                    ->get();
                break;
            case 'video':
                $appoinments = TherapistClientAppointment::with('user', 'therapist')
                    ->where('type', $filter)
                    ->get();
                break;
            case 'past':
                $appoinments = TherapistClientAppointment::with('user', 'therapist')
                    ->whereDate('appointment_date', '<', Carbon::now())
                    ->get();
                break;
            case 'future':
                $appoinments = TherapistClientAppointment::with('user', 'therapist')
                    ->whereDate('appointment_date', '>', Carbon::now())
                    ->get();
                break;
            case 'datefilter':
                if($request->input('from') != ''){
                    $from = Carbon::parse($request->input('from'))->format('Y-m-d');
                }
                if($request->input('to') != ''){
                    $to = Carbon::parse($request->input('to'))->format('Y-m-d');
                }
                $appoinments = TherapistClientAppointment::with('user', 'therapist')
                    ->whereDate('appointment_date', '>=', $from)
                    ->whereDate('appointment_date', '<=', $to)
                    ->get();

                break;
            default:
                $appoinments = TherapistClientAppointment::with('user', 'therapist')
                    ->get();
                break;
        }
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();


        $usersarray = [];
        $therapistarray = [];
        $usersarraytmp = [];
        $therapistarraytmp = [];
        $i = 0;

        foreach ($appoinments as $appointment) {
            if (is_null($appointment->user) || is_null($appointment->therapist))
                if (!is_null($appointment->user) && !in_array($appointment->user->id, $usersarraytmp)) {
                    $usersarray[$i]['id'] = $appointment->user->id;
                    $usersarray[$i]['name'] = $appointment->user->name;
                    $usersarraytmp[$i] = $appointment->user->id;
                }
            if (!is_null($appointment->therapist) && !in_array($appointment->therapist->id, $therapistarraytmp)) {
                $therapistarray[$i]['id'] = $appointment->therapist->id;
                $therapistarray[$i]['name'] = $appointment->therapist->fullname;
                $therapistarraytmp[$i] = $appointment->therapist->id;
            }
            $i++;

        }

        $data = [
            'appoinments' => $appoinments,
            'userslist' => $usersarray,
            'therapistlist' => $therapistarray,
            'filter' => $filter,
            'from' => $from,
            'to' => $to,
            'newtherapists' => $newtherapists

        ];
//dd($therapistarray);
        return View('appointmentsmanagement.show-appointments')->with($data);
    }

    public function filters(Request $request)
    {
        print_r($request);
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
        $therapistid = [];
        $clientId = [];
        $atype = [];


        if (isset($_POST['radio'])){
            $atype = $_POST['radio'];    // echo the choice
        }   // if ANY of the options was checked

        if ($request->input('username') != '') {
            $clientId = array('client_id' => $request->input('username'));
        }
        if ($request->input('therapistname') != '') {
            $therapistid = array('therapist_id' => $request->input('therapistname'));
        }

        $conditions = $clientId + $therapistid + $atype;

        $from = Carbon::parse('2019-01-01')->format('Y-m-d');
        $to = Carbon::parse('')->format('Y-m-d');

        if($request->input('fromdate') != ''){
            $from = Carbon::parse($request->input('fromdate'))->format('Y-m-d');
        }
        if($request->input('todate') != ''){
            $to = Carbon::parse($request->input('todate'))->format('Y-m-d');
        }


        $allappoinments = TherapistClientAppointment::with('user', 'therapist')->get();
        $appoinments = TherapistClientAppointment::with('user', 'therapist')
            ->where($conditions)
            ->whereDate('appointment_date', '>=', $from)
            ->whereDate('appointment_date', '<=', $to)
            ->get();

        $newtherapists = Therapistrequest::Where('view_profile', '0')->count();

        $usersarray = [];
        $therapistarray = [];
        $usersarraytmp = [];
        $therapistarraytmp = [];
        $i = 0;
        foreach ($allappoinments as $appointment) {
            if (!in_array($appointment->user->id, $usersarraytmp)) {
                $usersarray[$i]['id'] = $appointment->user->id;
                $usersarray[$i]['name'] = $appointment->user->name;
                $usersarraytmp[$i] = $appointment->user->id;
            }
            if (!in_array($appointment->therapist->id, $therapistarraytmp)) {
                $therapistarray[$i]['id'] = $appointment->therapist->id;
                $therapistarray[$i]['name'] = $appointment->therapist->fullname;
                $therapistarraytmp[$i] = $appointment->therapist->id;
            }
            $i++;

        }
        $data = [
            'appoinments' => $appoinments,
            'userslist' => $usersarray,
            'therapistlist' => $therapistarray,
            'userid' => $clientId,
            'therapistid' => $therapistid,
            'atype' => $atype,
            'fromdate' => $from,
            'todate' => $to,
            'newtherapists' => $newtherapists

        ];
        return View('appointmentsmanagement.show-appointments')->with($data);

    }

    /**
     * Display the specified resource.
     *
     * @param Appointment $appointment
     * @return Response
     */
    public function show($id)
    {

        $appointment = TherapistClientAppointment::with('user', 'therapist')->find($id);
        $appointment->time = date('H:i', strtotime($appointment->appointment_date));
        $appointment->date = date('d-m-Y', strtotime($appointment->appointment_date));
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        //dd($appointment);

        //dd($appointment);
//);
        return view('appointmentsmanagement.show-appointment', compact('appointment', 'newtherapists'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Appointment $appointment
     * @return Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Appointment $appointment
     * @return Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Appointment $appointment
     * @return Response
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
