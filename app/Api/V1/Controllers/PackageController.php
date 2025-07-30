<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\PackagesTransformer;
use App\Package;
use App\Therapist;
use App\User;
use ErrorException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct(){}

    public function index()
    {

        try{
            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $packages = Package::all();
            $packages = new Collection($packages, new PackagesTransformer());

            $manager = $manager->createData($packages);

            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Successfully retrieved!'
            ]);
        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }

        }

    public function get_client_subscribed_plan()
    {

        try{
            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $user = Auth::user();
            $packages = $user->subscribedPlans()->where('active',1)->first();
            if(is_null($packages)){
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Successfully retrieved!'
                ]);
            }
            $packages = new Item($packages, new PackagesTransformer());
            $manager = $manager->createData($packages);

            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Successfully retrieved!'
            ]);
        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }

    }


    public function get_therapist_subscribed_plan(Request $request)
    {

        try{
            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $user = User::where('id', $request->get('user_id'))->first();
            $packages = $user->subscribedPlans()->where('active',1)->first();
            if(is_null($packages)){
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Successfully retrieved!'
                ]);
            }
            $packages = new Item($packages, new PackagesTransformer());
            $manager = $manager->createData($packages);

            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Successfully retrieved!'
            ]);
        }catch (ErrorException $exception){
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
    public function create(){
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request){

        try{
            $this->validate($request, [
                'plan_id' => 'required|exists:packages,id'
            ]);

            $user = Auth::user();

            $user->subscribedPlans()->update(['active' => 0]);
            $user->subscribedPlans()->attach($request->get('plan_id'),['active' => 1]);

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Successfully Saved!'
            ]);
        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    /**
     * Display the specified resource.

     */
    public function show($id){}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Therapist  $therapist
     * @return Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.

     */
    public function update(Request $request, $id){}

    /**
     * Remove the specified resource from storage.
     *
     * @param  Therapist  $therapist
     * @return Response
     */
    public function destroy($id){}
}
