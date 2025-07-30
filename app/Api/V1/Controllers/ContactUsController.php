<?php

namespace App\Api\V1\Controllers;

use App\ContactUs;
use App\Http\Controllers\Controller;
use App\Question;
use ErrorException;
use Illuminate\Http\Response;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){}

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
        try{
            $user = Auth::user();
           // user_id, description, email, title
            ContactUs::create([
                'user_id' => $user->id,
                'email' => $request->get('email'),
                'title' => $request->get('title'),
                'description' => $request->get('description')
            ]);

                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Thank you for contacting us. We will reach out to you shortly.'
                ]);

        }catch (ErrorException $exception){
            dd($exception);
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
}
