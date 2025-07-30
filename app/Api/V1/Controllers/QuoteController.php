<?php


namespace App\Api\V1\Controllers;


use App\Favourite;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuoteReqest;
use App\Models\Quote;
use App\Models\Video;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuoteController extends Controller
{
    // store the quote

    public function store(Request $request)
    {

        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required',
            ], [
                'title.required' => 'The title is required.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => $validator->getMessageBag()->first('title'),
                ], 200);
            }

            //Save details in Quote table
            $quote = Quote::create($request->all());
            $userId = Auth::user()->id;
            $quote->user()->associate($userId)->save();

            return response()->json( [
                'status' => true,
                'message'=> 'Your Quote Has Been Added Successfully! After Admin\'s Approvement, You Will Be Able To Publish Your Quote!'
            ]);

        }
        catch (Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong! Please try again later.'
            ],200);
        }
    }

    //my quote api collection

    public function myQuotes($status = null){
        try {
            $user = Auth::user();
            if(is_null($status)) {
                $quotes = Quote::whereUserId($user->id)
                    ->latest()->get();
            }else{
                $quotes = Quote::whereUserId($user->id)
                    ->where('status', $status)
                    ->latest()->get();
            }
            $quoteCollection = [];
            foreach ($quotes as $key => $quote) {
                $quoteObj = [
                    'id' => $quote->id,
                    'title' => $quote->title,
                    'description' => $quote->description,
                    'heart_counts' => 1,
                    'status'=> ($quote->quote_status == 1)?'publish':'private',
                    'admin_status' => $quote->admin_status,
                    'created_at' => Carbon::parse($quote->created_at)->format('m/d/Y h:i A'),
                ];
                array_push($quoteCollection, $quoteObj);
            }

            return response()->json([
                'status' => true,
                'message'=> 'Quotes Retrieved Successfully!!',
                'data' => $quoteCollection,
            ]);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong! Please try again later.'
            ],200);
        }
    }

    public function myFavouriteQuote()
    {
        try {

            $user = Auth::user();

            if ($user->isClient()) {
                $quotes = Favourite::whereNotNull('quote_id')->where('favorite',1)->where('user_id', $user->id)->get()
                    ->each(function ($videoImageUrl) use ($user) {
                        $favouriteCount = Favourite::where('quote_id', $videoImageUrl->video_id)->where('favorite', 1)->count();
                        $videoImageUrl->favouriteCount = isset($favouriteCount) ? $favouriteCount : null;
                    });

                $data = array();
                foreach ($quotes as $key => $allItem) {
                    $videoObj = array(
                        "id"=> $allItem->id,
                        "user_id"=> $allItem->user_id,
                        "video_id"=> $allItem->video_id,
                        "quote_id"=> $allItem->quote_id,
                        'description' => isset($allItem->quote->description) ? $allItem->quote->description : null,
                        "content_type"=> $allItem->content_type,
                        "is_star"=> $allItem->is_like,
                        "watch_later"=> $allItem->watch_later,
                        "is_heart"=> $allItem->favorite,
                        "heart_counts"=> $allItem->favouriteCount,
                        'admin_status' => $allItem->admin_status,
                        'user' => [
                            'id' => isset($allItem->user->person) ? $allItem->user->id : null,
                            'name' => isset($allItem->user->name) ? $allItem->user->name : null,
                            'imageUrl' => isset($allItem->user->person->image_url) ? $allItem->user->person->image_url : null,
                        ],
                        "ago"=> $allItem->created_at->diffForHumans(),
                        "created_at"=> $allItem->created_at,
                        "updated_at"=> $allItem->updtad_at
                    );
                    array_push($data, $videoObj);
                }

                return response()->json([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Served'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => "Something went wrong"
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


    public function myPublishQuotes(){
        try {
            $user = Auth::user();

            $quotes = Quote::whereUserId($user->id)->where('quote_status','publish')->latest()->get()
                ->each(function ($videoImageUrl) use ($user) {
                    $favouriteCount = Favourite::where('quote_id', $videoImageUrl->id)->where('favorite', 1)->count();
                    $videoImageUrl->favouriteCount = isset($favouriteCount) ? $favouriteCount : null;
                });


            $videoCollection = [];
            foreach ($quotes as $key => $quote) {
                $quoteObj = [
                    'id' => $quote->id,
                    'title' => $quote->title,
                    'description' => $quote->description,
                    'heart_counts' => $quote->favouriteCount,
                    'status' => "publish",
                    'admin_status' => $quote->admin_status,
                    'created_at' => Carbon::parse($quote->created_at)->format('M-d-Y'),

                ];
                array_push($videoCollection, $quoteObj);
            }

            return response()->json([
                'status' => true,
                'message'=>'quotes retrieved Successfully!!',
                'data' => $videoCollection,
            ]);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong! Please try again later.'
            ],200);
        }
    }


    /**
     * @return JsonResponse
     */
    public function myPrivateQuotes(){
        try {
            $user = Auth::user();

            $quotes = Quote::whereUserId($user->id)->where('quote_status', 'private')->latest()->get()
                ->each(function ($videoImageUrl) use ($user) {
                    $favouriteCount = Favourite::where('quote_id', $videoImageUrl->id)->where('favorite', 1)->count();
                    $videoImageUrl->favouriteCount = isset($favouriteCount) ? $favouriteCount : null;
                });

            $videoCollection = [];
            foreach ($quotes as $key => $quote) {
                $videoObj = [
                    'id' => $quote->id,
                    'title' => $quote->title,
                    'description' => $quote->description,
                    'heart_counts' => $quote->favouriteCount,
                    'status' => "private",
                    'admin_status' => $quote->admin_status,
                    'created_at' => Carbon::parse($quote->created_at)->format('M-d-Y'),
                ];
                array_push($videoCollection, $videoObj);
            }

            return response()->json([
                'status' => true,
                'message'=>'quotes retrieved Successfully!!',
                'data' => $videoCollection,
            ]);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong! Please try again later.'
            ],200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function editQuote(Request $request)
    {
        try{
            $quoteData = [
                'title' => $request->get('title'),
//                'image_url' => $request->get('image_url'),
                'description' => $request->get('description'),
            ];
            $id = $request->get('id');
            $quote = Quote::whereId($id)->first();

            //Save details in Quote table
            if (isset($quote) && !empty($quote)){
                $quote->update($quoteData);
                return response()->json( ['status' => true, 'message'=> 'Quotes Updated Successfully !!']);
            }else{
                return response()->json( ['status' => false, 'message'=> 'Something went wrong!!']);
            }
        }
        catch (Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}