<?php

namespace App\Api\V1\Controllers;

use App\Advisorrequest;
use App\Answers;
use App\BpAnswers;
use App\Favourite;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Video;
use App\Person;
use App\Question;
use App\RateCard;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    /**
     * @return JsonResponse
     * Advisor & client's dashboard data
     */
    public function index()
    {
        try {

            $user = Auth::user();

            if ($user->isAdvisor()) {
                $video = Video::where('user_id', $user->id)->where('admin_status','!=','rejected')->orderByDesc('created_at')->first();

                $quote = Quote::where('user_id', $user->id)->where('admin_status','!=','rejected')->orderByDesc('created_at')->first();

                $data = array();
                if (isset($video) && !is_null($video)){
                    $likeCountVideo = Favourite::where('video_id', $video->id)->where('is_like', 1)->count();
                    $favouriteCountVideo = Favourite::where('video_id', $video->id)->where('favorite', 1)->count();
                    $videoObj = array(
                        'id' => $video->id,
                        'type' => "Video",
                        'video' => [
                            'title' => $video->title,
                            'url' => $video->url,
                            'thumbnail' => $video->thumbnail,
                            'star_counts' => $likeCountVideo,
                            'heart_counts' => $favouriteCountVideo,
                            'view_count' => $video->views,
                            'video_type'=> $video->video_type,
                            'status'=> $video->video_status,
                            'admin_status'=> $video->admin_status,
                        ],
                        'user' => [
                            'id' => isset($video->user->person) ? $video->user->id : null,
                            'imageUrl' => isset($video->user->person->image_url) ? $video->user->person->image_url : null,
                            'name' => isset($video->user->name) ? $video->user->name : null,
                        ],
                        'quote' => null,
                        "ago"=> $video->created_at->diffForHumans(),
                        'created_at' => $video->created_at->format('d/m/y'),
                    );
                    array_push($data, $videoObj);
                }else{
                    $videoObj = array(
                        'id' => null,
                        'type' => null,
                        'video' => [
                            'title' => null,
                            'url' => null,
                            'thumbnail' => null,
                            'star_counts' => null,
                            'heart_counts' => null,
                            'view_count' => null,
                            'video_type'=> null,
                            'status'=> null,
                            'admin_status'=> null,
                        ],
                        'user' => [
                            'id' => null,
                            'imageUrl' => null,
                            'name' => null,
                        ],
                        'quote' => null,
                        "ago"=> null,
                        'created_at' => null,
                    );
                    array_push($data, $videoObj);
                }

                if (isset($quote) && !is_null($quote)) {
                    $favouriteCountQuote = Favourite::where('quote_id', $quote->id)->where('favorite', 1)->count();

                    $quoteObj = array(
                        'id' => $quote->id,
                        'type' => "Quote",
                        'quote' => [
                            'title' => $quote->title,
                            'description' => $quote->description,
                            'heart_counts' => $favouriteCountQuote,
                            "ago" => $quote->created_at->diffForHumans(),
                            'status'=> $quote->quote_status,
                            'admin_status'=> $quote->admin_status,
                        ],
                        'user' => [
                            'id' => isset($quote->user->person) ? $quote->user->id : null,
                            'name' => isset($quote->user->name) ? $quote->user->name : null,
                            'imageUrl' => isset($quote->user->person->image_url) ? $quote->user->person->image_url : null,
                        ],
                        'video' => null,
                        'created_at' => $quote->created_at->format('d/m/y'),
                    );

                    array_push($data, $quoteObj);
                }else{
                    $quoteObj = array(
                        'id' => null,
                        'type' => null,
                        'quote' => [
                            'title' => null,
                            'description' => null,
                            'heart_counts' => null,
                            "ago" => null,
                            'status'=> null,
                            'admin_status'=> null,
                        ],
                        'user' => [
                            'id' => null,
                            'name' => null,
                            'imageUrl' => null,
                        ],
                        'video' => null,
                        'created_at' => null,
                    );

                    array_push($data, $quoteObj);
                }

                if (isset($data) && !is_null($data)){
                    $collectionVideoQuote = collect($data);
                    return response()->json([
                        'status' => true,
                        'data' => $collectionVideoQuote->values(),
                        'message' => 'Served'
                    ]);
                }else{
                    return response()->json([
                        'status' => true,
                        'message' => "Something went wrong"
                    ]);
                }


            } elseif ($user->isClient()) {

                $videos = Video::with('user')->where('video_status', 'publish')->orderByDesc('created_at')->get()
                    ->each(function ($videoImageUrl) use ($user) {
                        $favouriteCount = Favourite::where('video_id', $videoImageUrl->id)->where('favorite', 1)->count();
                        $likeCount = Favourite::where('video_id', $videoImageUrl->id)->where('is_like', 1)->count();
                        $favourite = Favourite::where('video_id', $videoImageUrl->id)->where('user_id', $user->id)->where('favorite', 1)->count();
                        $like = Favourite::where('video_id', $videoImageUrl->id)->where('user_id', $user->id)->where('is_like', 1)->count();
                        $watchLater = Favourite::where('video_id', $videoImageUrl->id)->where('user_id', $user->id)->where('watch_later', 1)->count();
                        $videoImageUrl->user_id = isset($videoImageUrl->user) ? $videoImageUrl->user->id : null;
                        $videoImageUrl->image_url = isset($videoImageUrl->user->person) ? $videoImageUrl->user->person->image_url : null;
                        $videoImageUrl->favouriteCount = isset($favouriteCount) ? $favouriteCount : null;
                        $videoImageUrl->likeCount = isset($likeCount) ? $likeCount : null;
                        $videoImageUrl->favourite = isset($favourite) ? $favourite : null;
                        $videoImageUrl->like = isset($like) ? $like : null;
                        $videoImageUrl->watchLater = isset($watchLater) ? $watchLater : null;
                    });
                $quotes = Quote::with('user')->where('quote_status', 'publish')->orderByDesc('created_at')->get()
                    ->each(function ($quoteImageUrl) use ($user) {
                        $favouriteCount = Favourite::where('quote_id', $quoteImageUrl->id)->where('favorite', 1)->count();
                        $favourite = Favourite::where('quote_id', $quoteImageUrl->id)->where('user_id', $user->id)->where('favorite', 1)->count();
                        $quoteImageUrl->image_url = isset($quoteImageUrl->user->person) ? $quoteImageUrl->user->person->image_url : null;
                        $quoteImageUrl->favouriteCount = isset($favouriteCount) ? $favouriteCount : null;
                        $quoteImageUrl->favourite = isset($favourite) ? $favourite : null;

                    });

                $data = array();
                foreach ($videos as $key => $allItem) {
                    $videoObj = array(
                        'id' => $allItem->id,
                        'type' => "Video",
                        'video' => [
                            'title' => $allItem->title,
                            'url' => $allItem->url,
                            'thumbnail' => $allItem->thumbnail,
                            'star_counts' => $allItem->likeCount,
                            'heart_counts' => $allItem->favouriteCount,
                            'is_heart' => $allItem->favourite,
                            'is_star' => $allItem->like,
                            'watch_later' => $allItem->watchLater,
                            'video_type'=> $allItem->video_type,
                            "ago"=> $allItem->created_at->diffForHumans(),
                        ],
                        'user' => [
                            'id' => isset($allItem->user->person) ? $allItem->user->id : null,
                            'imageUrl' => isset($allItem->user->person->image_url) ? $allItem->user->person->image_url : null,
                            'name' => isset($allItem->user->name) ? $allItem->user->name : null,
                        ],
                        'quote' => null,
                        'created_at' => $allItem->created_at,
                    );
                    array_push($data, $videoObj);
                }


                foreach ($quotes as $key => $allItem) {
                    $quoteObj = array(
                        'id' => $allItem->id,
                        'type' => "Quote",
                        'quote' => [
                            'title' => $allItem->title,
                            'description' => $allItem->description,
                            'heart_counts' => $allItem->favouriteCount,
                            'is_heart' => $allItem->favourite,
                            "ago"=> $allItem->created_at->diffForHumans(),
                        ],
                        'user' => [
                            'id' => isset($allItem->user->person) ? $allItem->user->id : null,
                            'name' => isset($allItem->user->name) ? $allItem->user->name : null,
                            'imageUrl' => isset($allItem->user->person->image_url) ? $allItem->user->person->image_url : null,
                        ],
                        'video' => null,
                        'created_at' => $allItem->created_at,
                    );
                    array_push($data, $quoteObj);
                }

                $collectionVideoQuote = collect($data)->sortBy('created_at')->reverse();
                return response()->json([
                    'status' => true,
                    'data' => $collectionVideoQuote->values(),
                    'message' => 'Served'
                ]);
            }
        }
         catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * Add video favourite,watch-later & like
     * remove video from favourite,watch-later & like
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
//                'message' => $exception->getMessage(),
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    public function favourite(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'content_type' => 'required|string|max:255',
                'request_type' => 'required|string|max:255',
                'action' => 'required|string|max:255'
            ], [
                'content_type.required' => 'The content type is required.',
                'request_type.required' => 'Request Type is required',
                'action.required' => 'Action is required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => $validator->getMessageBag(),
                ], 200);
            }
            $user = Auth::user();
            $lowerContentType = strtolower($request->get('content_type'));
            $lowerRequestType = strtolower($request->get('request_type'));
            $lowerAction      = strtolower($request->get('action'));
            if ($lowerContentType == 'video'){
                $video = Video::find($request->get('id'));
                if (isset($video) && !is_null($video)){
                    $favouriteExists =  Favourite::where('video_id',$request->get('id'))->where('user_id',$user->id)->first();
                    if (isset($favouriteExists) && $favouriteExists->video_id == $request->get('id')){
                        if ($lowerRequestType == 'watch-later'){
                            if ($request->get('action') == 'watch'){
                                $favouriteExists->fill(['watch_later' => 1])->update();
                                $message = "Video Added In Your Playlist (Watch Later)";
                            }elseif($lowerAction == 'remove-watch'){
                                $favouriteExists->fill(['watch_later' => 0])->update();
                                $message = "Video Removed From Your Playlist (Watch Later)";
                            }
                        }elseif ($lowerRequestType == 'like'){
                            if ($lowerAction == 'like'){
                                $favouriteExists->fill(['is_like' => 1])->update();
                                $message = "Video Added To Like Videos";
                            }elseif ($lowerAction == 'remove-like'){
                                $favouriteExists->fill(['is_like' => 0])->update();
                                $message = "Video removed from Like Videos";
                            }
                        }elseif($lowerRequestType == 'favorite'){
                            if ($lowerAction == 'favorite'){
                                $favouriteExists->fill(['favorite' => 1])->update();
                                $message = "Video Added To Favorite Videos";


                            }elseif($lowerAction == 'remove-favorite'){
                                $favouriteExists->fill(['favorite' => 0])->update();
                                $message = "Video Removed From Favorite List";
                            }
                        }
                    }else{
                        if ($lowerRequestType == 'watch-later'){
                            if ($lowerAction == 'watch'){
                                Favourite::create(['user_id' => $user->id,'video_id' => $request->get('id'),'favorite' => 1,'content_type'=>$lowerContentType]);
                                $message = "Video Added In Your Playlist (Watch Later)";

                            }
                        }elseif ($lowerRequestType == 'like'){
                            if ($lowerAction == 'like'){
                                Favourite::create(['user_id' => $user->id,'video_id' => $request->get('id'),'is_like' => 1,'content_type'=>$request->get('content_type')]);
                                $message = "Video Added To Like Videos";

                            }
                        }elseif($lowerRequestType == 'favorite'){
                            if ($lowerAction == 'favorite'){
                                Favourite::create(['user_id' => $user->id,'video_id' => $request->get('id'),'favorite' => 1,'content_type'=>$lowerContentType]);
                                $message = "Video Added To Favorite Videos";
                            }
                        }
                    }
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Video not exists'
                    ]);
                }
            }elseif ($lowerContentType == 'quote') {
                $quote = Quote::find($request->get('id'));
                if (isset($quote) && !is_null($quote)) {
                    $favouriteExists = Favourite::where('quote_id', $request->get('id'))->where('user_id', $user->id)->first();
                    if (isset($favouriteExists) && $favouriteExists->quote_id == $request->get('id')) {
                        if ($lowerRequestType == 'favorite') {

                            if ($lowerAction == 'favorite') {
                                $favouriteExists->fill(['favorite' => 1])->update();
                                $message = "Video Added To Favorite Quotes";
                            } elseif ($lowerAction == 'remove-favorite') {
                                $favouriteExists->fill(['favorite' => 0])->update();
                                $message = "Video Removed To Favorite Quotes";
                            }
                        }
                    } else {
                        if ($lowerRequestType == 'favorite') {
                            if ($lowerAction == 'favorite') {
                                Favourite::create(['user_id' => $user->id, 'quote_id' => $request->get('id'), 'favorite' => 1, 'content_type' => $lowerContentType]);
                                $message = "Quote Added To Favorite Quotes";
                            }
                        }
                    }
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Quote not exists'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Your Process Not Fulfilled'
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'message' => "Something went wrong"
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
            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'No answers are posted'
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */

    public function saveDashboard(Request $request)
    {
        try {
            $user = Auth::user();
            $answers = $request->get('questions');
            $allQuestionIDs = Collect($answers)->pluck('question_id');

            if (count($answers) > 0) {
                $advisorRequest = Advisorrequest::where('user_id', $user->id)->first();

                if ($request->has('rate_card_id') && $request->get('rate_card_id') > 0) {
                    $user->answers()->whereIn('question_id', $allQuestionIDs)
                        ->whereIn('rate_card_id', $request->get('rate_card_id'))->delete();
                    $rateCard = RateCard::find($request->get('rate_card_id'));
                } else {
                    if ($request->has('isFilledQuestion')) {
                        $user->update(['is_questionare_completed' => $request->get('isFilledQuestion')]);
                    }
                    //Add rate card into db and then use it's id in answers.
                    $rateCard = RateCard::create([
                        'advisor_id' => $user->id,
                        'advisor_name' => $request->get('advisor_name'),
                        'firm_name' => $request->get('firm_name')
                    ]);
                }

                if ($rateCard) {
                    foreach ($answers as $answer) {
                        if (!is_null(Auth::user()->roles()->where('name', 'Advisor')->first())) {
                            $options = isset($answer['options']) ? $answer['options'] : [];
                            if (count($options) > 0) {
                                $commaSeperatedString = '';
                                foreach ($options as $key => $option) {
                                    if ($key == 0) {
                                        $commaSeperatedString .= $option['title'];
                                    } else {
                                        $commaSeperatedString .= ', ' . $option['title'];
                                    }
                                }

                            }


                            $person = Person::where('id', $user->person->id)->first();
                            if (!is_null($person)) {
                                switch ($answer['question_title']) {
                                    case "Please indicate how advisors are compensated in your firm.":
                                        $advisorRequest->update(['compensation_type' => $commaSeperatedString == '' ? $answer['inputAnsText'] : $commaSeperatedString]);
                                        break;
                                }

                            }
                        }

                        if (count($options) > 0) {
                            foreach ($options as $option) {
                                Answers::create([
                                    'question_text' => $answer['question_title'],
                                    'question_id' => $answer['question_id'],
                                    'user_id' => $user->id,
                                    'rate_card_id' => $rateCard->id,
                                    'is_rate_card' => 1,
                                    'answer_text' => $option['title'],
                                    'option_id' => $option['id'],
                                ]);
                            }
                        } else {
                            Answers::create([
                                'question_text' => $answer['question_title'],
                                'question_id' => $answer['question_id'],
                                'user_id' => $user->id,
                                'rate_card_id' => $rateCard->id,
                                'is_rate_card' => 1,
                                'answer_text' => $answer['inputAnsText'],
                                'option_id' => null,
                            ]);
                        }
                    }
                    return response()->json([
                        'status' => true,
                        'data' => null,
                        'message' => 'Answers Successfully Saved!'
                    ]);
                } else {
                    return response()->json([
                        'status' => true,
                        'data' => null,
                        'message' => 'Problem adding rate card.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Something Went Wrong!'
                ]);
            }
        } catch (ErrorException $exception) {
            dd($exception);
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
