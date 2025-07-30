<?php

namespace App\Api\V1\Controllers;

use App\Answers;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\QuestionTransformer;
use App\Api\V1\Transformers\QuestionTypeTransformer;
use App\BpAnswers;
use App\BpOptions;
use App\BpQuestion;
use App\Http\Controllers\Controller;
use App\Options;
use App\Question;
use App\QuestionType;
use App\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;


class UploadController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function uploadImage(Request $request)
    {
        try {
            if ($files = $request->file('image')) {
                $request->image->move(public_path('images'), $files->getClientOriginalName());
                return response()->json([
                    'status' => true,
                    'url'=> url('/images/'.$files->getClientOriginalName()),
                    'message' => 'Image added successfully'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'url'=> '',
                    'message' => 'Image not uploaded.'
                ]);
            }
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
    public function uploadFile(Request $request)
    {
        try {
            if ($files = $request->file('file')) {
                $request->file->move(public_path('files'), $files->getClientOriginalName());
                return response()->json([
                    'status' => true,
                    'url' => url('/files/' . $files->getClientOriginalName()),
                    'message' => 'Image added successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'url' => '',
                    'message' => 'Image not uploaded.'
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
