<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Helpers\FcmController;
use App\Api\V1\Transformers\FeedbackTransformer;
use App\Api\V1\Transformers\NotesTransformer;
use App\clientFeedbackFromTherapist;
use App\Http\Controllers\Controller;
use App\Note;
use App\Question;
use App\Therapist;
use App\TherapistRatingReviews;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\User as AppUser;
use Illuminate\Support\Facades\Log;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;



class TherapistRatingReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request){
        try{
            $user = Auth::user();
            if($request->exists('therapist_id')){
                $therapist_id = $request->get('therapist_id');
            }else{
                $therapist_id = $user->therapist->id;
            }
            $therapistRatingReviews = TherapistRatingReviews::where('therapist_id', $therapist_id)->with('user')->get();
            if($therapistRatingReviews){
                return response()->json([
                    'status' => true,
                    'data' => $therapistRatingReviews,
                    'message' => 'List of reviews.'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Something Went Wrong!'
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
            $therapistRatingReviews = TherapistRatingReviews::create([
                'user_id' => $user->id,
                'therapist_id' => $request->get('therapist_id'),
                'ratings' => $request->get('ratings'),
                'review' => $request->get('review')
            ]);
            if($therapistRatingReviews){


                // Deactivate Feedbacks and journals.
                
                $user->shareJournals()->where('clients_shared_feedback_and_journals_with_therapist.therapist_id', $request->get('therapist_id'))->where('type','=','journal')->update(['active'=> 0]);
                $user->shareFeedback()->where('clients_shared_feedback_and_journals_with_therapist.therapist_id', $request->get('therapist_id'))->where('type','=','feedback')->update(['active'=> 0]);

                // Deactivate Therapist.
                $user->selectedTherapists()->update(['end_date' => Carbon::now(), 'is_active' => 0]);

                $therapist = Therapist::where('id', $request->get('therapist_id'))->first();
                $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                $description = $user->name . ' ' . 'has stoped your consultation.';
                $fcmController->pushNotification('Client Review', $description, 'review', $therapist->user_id);


                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Thank you for your feedback.'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Something Went Wrong!'
                ]);
            }
        }catch (ErrorException $exception){
            Log::error($exception);
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
    public function storeFeedback(Request $request)
    {
        try{
            $user = Auth::user();
            $storedFeedback = clientFeedbackFromTherapist::create([
                'therapist_id' => $user->therapist->id,
                'user_id' => $request->get('user_id'),
                'feedback' => $request->get('feedback')
            ]);

            $clientUser = AppUser::find($request->get('user_id'));
            $clientUser->shareFeedback()->attach($storedFeedback->id,[
                 'therapist_id' => $user->therapist->id,
                 'type' => 'feedback',
                 'active' => 1
              ]);


            $fcmController = new FcmController(env('FCM_SERVER_KEY'));
            $description = $user->name . ' ' . 'has given you feedback.';
            $response = $fcmController->pushNotification('Therapist Feedback', $description, 'review', $request->get('user_id'));

            if($storedFeedback){
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Thank you for your feedback.'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Something Went Wrong!'
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function getFeedback(Request $request)
    {
        try{
            $user = Auth::user();

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $getFeedback = clientFeedbackFromTherapist::where('user_id',$user->id)->orderBy('created_at', 'DESC')->get();
            $getFeedback = new Collection($getFeedback, new FeedbackTransformer($request));

            $manager = $manager->createData($getFeedback);

            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'successfully retrieved!'
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

    public function getClientNotes(Request $request){

        try{

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());


            $notes = Note::where('client_id',$request->get('client_id'))->orderBy('created_at', 'DESC')->get();
            $notes = new Collection($notes, new NotesTransformer($request));

            $manager = $manager->createData($notes);

            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Successfully retrieved!'
            ]);

        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
                'data' => null,
            ]);
        }
    }

    public function saveClientNotes(Request $request){
        $this->validate($request, [
            'client_id' =>'required|exists:users,id',
            'note' =>'required'
        ]);

        try{

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $user = Auth::user();

            if(is_null($user->therapist)){
                return response()->json([
                    'status' => false,
                    'message' => 'Therapist not found!',
                    'data' => null,
                ]);
            }

            Note::create([
                'therapist_id' => $user->therapist->id,
                'client_id' => $request->get('client_id'),
                'note' => $request->get('note')
            ]);

            $notes = Note::where('client_id',$request->get('client_id'))->get();

            $notes = new Collection($notes, new NotesTransformer($request));

            $manager = $manager->createData($notes);

            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Your note has been saved'
            ]);

        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
                'data' => null,
            ]);
        }
    }
}
