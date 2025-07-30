<?php

namespace App\Http\Controllers;

use App\Models\AdvisorClientType;
use App\models\ClientTypes;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
class AdvisorClientTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $advisors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Advisor');
        })->get();


        return view('advisorclienttype.index',compact('advisors'));
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

            $advisorId = $request['advisor'];
            $advisor = AdvisorClientType::where('user_id',$advisorId)->first();
            $types = $request->get('types');
            if(isset($types)){
            if(!is_null($advisor)){
                AdvisorClientType::where('user_id',$advisorId)->delete();
                foreach ($types as $type) {
                    AdvisorClientType::create([
                        'client_type' => $type,
                        'user_id' => $advisorId,
                    ]);
                }
            }else{
                foreach ($types as $type) {
                    AdvisorClientType::create([
                        'client_type' => $type,
                        'user_id' => $advisorId,

                    ]);
                }
            }
                return response()->json(['status' => true,'message'=> 'Advisor Client Type Payer Added Successfully !']);

            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'Type is not added yet!!'
                ]);
            }


        }
        catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
