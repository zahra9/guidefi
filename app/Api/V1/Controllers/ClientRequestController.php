<?php

namespace App\Api\V1\Controllers;

use App\Answers;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\QuestionTransformer;
use App\Api\V1\Transformers\QuestionTypeTransformer;
use App\BpAnswers;
use App\BpOptions;
use App\BpQuestion;
use App\Clientrequest;
use App\Http\Controllers\Controller;
use App\MatchedAdvisorList;
use App\Options;
use App\Question;
use App\QuestionType;
use App\User;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;


class ClientRequestController extends Controller
{

    /**
     * List all the clients for an advisor
     *
     * @return Response
     */
    public function allClients(Request $request)
    {
        try {
//            $clientRequests = Clientrequest::with('person')->get();


            $clientData = [];

            for($i = 0; $i<=8; $i++){
                $clientRequests[] = $i;
            }
            foreach ($clientRequests as $key => $client){

                $clientData[] = [
                    "client_id" => $key+1, //$client->person->id,
                    "is_online" => 1,
                    "client_name" => 'Muhammad Fahad', //$client->person->first_name . ' ' . $client->person->first_name,
                    "image" => "https://picsum.photos/200",
                    "conract" => "Financial Planning",
                    "started" => "15/8/2020",
                    "ended" => "15/12/2020",
                    "status" => "active"
                ];
            }
            if($clientRequests){
                return response()->json([
                    'status' => true,
                    'data' => $clientData,
                    'message' => 'Clients retrived Successfully'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No Details found.'
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


    /**
     * List one single client details for an advisor
     *
     * @return Response
     */
    public function getClient(Request $request)
    {
        try {
            $clientRequests = Clientrequest::with('person')->get();

            $clientData = [];


                $clientData[] = [
                    "client_id" => $request->id,
                    "is_online" => 1,
                    "client_name" => "Muhammad Fahad",
                    "image" => "https://picsum.photos/200",
                    "conract" => "Financial Planning",
                    "started" => "15/8/2020",
                    "ended" => "15/12/2020",
                    "status" => "active"
                ];

            if($clientRequests){
                return response()->json([
                    'status' => true,
                    'data' => $clientData,
                    'message' => 'Client retrived sussesfully'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No Clients found.'
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

    /**
     * List all the clients for an advisor
     *
     * @return Response
     */
    public function allPotentialClients(Request $request)
    {
        try {

            $user = Auth::user(); // Advisor hit

            $clientRequests = MatchedAdvisorList::where('client_request_status', 1)->where('advisor_id', $user->id)->whereHas('client', function ($q) {})->get();

            $clientData = [];

            foreach ($clientRequests as $key => $record){

                $request_status = 'active';
                if(Carbon::parse($record->advisor_request_date)->addDays(2)->diffInHours(Carbon::now()) < 0){
                    $request_status = 'expired';
                }

                $price = $record->client->clientRequest !== null ? $record->client->clientRequest->price : 20;

                $clientData[] = [
                    "match_id" => $record->id, //$client->person->id,
                    "client_id" => $record->client_id, //$client->person->id,
                    "name" => $record->client->person->first_name . ' ' . $record->client->person->last_name,
                    "gender" => $record->client->person->gender,
                    "age" => $record->client->person->age . ' Years',
                    "income_level" => $record->client->clientRequest !== null ? $record->client->clientRequest->income_level : "$100k/year",
                    "investment_level" => $record->client->clientRequest !== null ? $record->client->clientRequest->investment_level : "$100k/year",
                    "assets" => $record->client->clientRequest !== null ? $record->client->clientRequest->assets : "$100kyear",
                    "service" => $record->client->clientRequest !== null ? $record->client->clientRequest->service : "Retirement Planning",
                    "request_date" => Carbon::parse($record->advisor_request_date)->format('d/m/Y'),
                    "expiry_time" => Carbon::parse($record->advisor_request_date)->diffInHours(Carbon::now()) . ' Hours',
                    "price" => floatval(preg_replace('/[^\d.]/', '', number_format($price, 2))),
                    "request_status" => $request_status, // active/expired
                ];
            }
            if($clientRequests){
                return response()->json([
                    'status' => true,
                    'data' => $clientData,
                    'message' => 'Clients retrived sussesfully'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No Details found.'
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
