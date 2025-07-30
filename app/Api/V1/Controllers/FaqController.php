<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Transformers\FaqTransformer;
use App\Faq;
use App\Api\V1\Serializers\NoDataArraySerializer;
use ErrorException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use \Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     * get Faqs for client & advisor
     */
    public function index()
    {
        try {
            $user = Auth::user();

            /*** Role_id = 2 for Advisor ***/
            if ($user->isAdvisor()){
                $faqs = Faq::where('role_id',2)->get();
                return response()->json([
                    'status' => true,
                    'data' => $faqs,
                    'message' => 'Served'
                ]);
            }elseif($user->isClient()){
                /*** Role_id = 3 for Client ***/
                $faqs = Faq::where('role_id',3)->get();
                return response()->json([
                    'status' => true,
                    'data' => $faqs,
                    'message' => 'Served'
                ]);
            }else{
                return response()->json([
                    'status' => true,
                    'data' => [],
                    'message' => 'Logged user should be client or advisor'
                ]);
            }


        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }


//        try{
//            $client = 0;
//            if(Auth::user()->isClient()) $client = 1;
//            $manager = new Manager();
//            $manager->setSerializer(new NoDataArraySerializer());
//
//            $faqs = Faq::orderBy('order','asc')->where('is_client', $client)->get();
//            $faqs = new Collection($faqs, new FaqTransformer());
//
//            $manager = $manager->createData($faqs);

//            return response()->json([
//                'status' => true,
//                'data' => $manager->toArray(),
//                'message' => 'Successfully retrieved!'
//            ]);
//        }catch (\ErrorException $exception){
//            return response()->json([
//                'status' => false,
//                'data' => null,
//                'message' => 'Something Went Wrong!'
//            ]);
//        }

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
        //
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
