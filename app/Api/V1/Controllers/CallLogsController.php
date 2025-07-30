<?php

namespace App\Api\V1\Controllers;

use App\AdvisorClientAppointment;
use App\Api\V1\Transformers\FaqTransformer;
use App\PlanUsageLogs;
use App\Api\V1\Serializers\NoDataArraySerializer;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;


class CallLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $user = Auth::user();

            if ($user->isClient()) {
                $callLogs = $user->clientCallLogs()->get();
            } else {
                $callLogs = $user->advisorCallLogs()->get();
            }
            return response()->json([
                'status' => true,
                'data' => $callLogs,
                'message' => 'Successfully retrieved!'
            ]);
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
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
        try {
            $this->validate($request, [
                'type' => 'required|in:audio,video',
                'start_time' => 'required',
                'end_time' => 'required',
                'with_user' => 'required',
            ]);

            $user = Auth::user();

            if($request->has('appointment_id') && $request->get('appointment_id')){
                $appointment = AdvisorClientAppointment::find($request->get('appointment_id'));
//                if ($appointment && Carbon::parse($appointment->appointment_date . ' ' . $appointment->end_time)->lt(Carbon::now())){
                    $appointment->call_status = 1;
                    $appointment->save();
//                }
            }

            if ($user->isClient()) {
                $user->clientCallLogs()->create([
                    'type' => $request->get('type'),
                    'start_time' => $request->get('start_time'),
                    'end_time' => $request->get('end_time'),
                    'appointment_id' => $request->get('appointment_id'),
                    'client_id' => $user->id,
                    'advisor_id' => $request->get('with_user'),
                ]);
            } else {
                $user->advisorCallLogs()->create([
                    'type' => $request->get('type'),
                    'start_time' => $request->get('start_time'),
                    'end_time' => $request->get('end_time'),
                    'appointment_id' => $request->get('appointment_id'),
                    'client_id' => $request->get('with_user'),
                    'advisor_id' => $user->id
                ]);
            }


            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'successfully saved!'
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
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
