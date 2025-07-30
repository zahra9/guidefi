<?php


namespace App\Api\V1\Controllers;

use App\Favourite;
use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Quote;
use App\Models\Video;
use App\VideoView;
use Carbon\Carbon;
use Dawson\Youtube\Facades\Youtube;
use ErrorException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use FFMpeg;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * advisor
     * @param VideoRequest $request
     * @return JsonResponse
     */
    public function store(VideoRequest $request)
    {
//        try {
            $image = $request->file('video');

            set_time_limit(0);
            $ffmpeg = FFMpeg\FFMpeg::create([
                'ffmpeg.binaries'  =>env('FFMPEG_BINARIES','/usr/bin/ffmpeg'),
                'ffprobe.binaries' => env('FFPROBE_BINARIES','/usr/bin/ffprobe'),

            ]);

            // save thumbnail
            $video = $ffmpeg->open($image);

            $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(3));
            $thumbnailName = 'thumbnail-s3' .time() . '.' .'jpg';
            $frame->save($thumbnailName);
            //upload thumbnail on aws
            $fileAwsThumbnail = Storage::disk('s3')->put($thumbnailName, file_get_contents($thumbnailName), 'public');
            $thumbnailUrl = Storage::disk('s3')->url($thumbnailName);
            $extension =  $image->getClientOriginalExtension();

        if($extension != 'mp4'){
                // convert video in mp4 and save video
                $fileName = 'video-s3' .time() . '.' .'mp4';
                $video->save(new FFMpeg\Format\Video\X264('libmp3lame', 'libx264'), $fileName);
            //  upload video on aws
            $fileAws = Storage::disk('s3')->put($fileName, file_get_contents($fileName), 'public');

            unlink($fileName);
            }

            else{
                $fileName = 'videos-s3' . time() . '.' . $image->getClientOriginalExtension();
                //  upload video on aws
                Storage::disk('s3')->put($fileName, file_get_contents($image), 'public');


            }

            $videoURL = Storage::disk('s3')->url($fileName);

            $videoData = [
                'title' => $request->get('title'),
                'url' => $videoURL,
                'thumbnail' => $thumbnailUrl
            ];


            //Save details in video table

            $video = Video::create($videoData);
            $userId = Auth::user()->id;
            $video->user()->associate($userId)->save();
            unlink($thumbnailName);


            return response()->json([
                'status' => true,
                'message' => 'Video Uploaded Successfully!!'
            ]);
      /*  } catch (Exception $e) {
            return response()->json([
                'status' => false,

                'message' => 'Something Went Wrong! Please try again later.'
            ], 200);
        }*/
    }

    /**
     * manage status for advisor
     * @param Request $request
     * @return JsonResponse
     */

    public function manageStatus(Request $request)
    {
        try {
            $type = $request->get('type');
               if ($type == 'video') {
                $video = Video::findOrFail($request->get('id'));
                if ($request->get('status') == 'delete') {
                    $video->delete();
                } else {
                    $video->update(['video_status' => $request->get('status')]);
                }
            } elseif ($type == 'quote') {
                $quote = Quote::findOrFail($request->get('id'));
                if ($request->get('status') == 'delete') {
                    $quote->delete();
                } else {
                    $quote->update(['quote_status' => $request->get('status')]);
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Status updated!!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong! Please try again later.'
            ], 200);
        }
    }

    /**
     * edit title for advisor
     * @param Request $request
     * @return JsonResponse
     */
    public function editTitle(Request $request)
    {
        try {
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
            $video = Video::whereId($request->get('id'))->first();
            $video->update(['title' => $request->get('title')]);
            return response()->json([
                'status' => true,
                'message' => 'Video Title updated!!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    //my video api collection

    /**
     * Advisor
     * @return JsonResponse
     */

    public function myPublishVideos()
    {
        try {
            $user = Auth::user();

            $videos = Video::
            whereUserId($user->id)-> where('video_status', 'publish')->latest()->get()
                ->each(function ($videoImageUrl) use ($user) {
                    $favouriteCount = Favourite::where('video_id', $videoImageUrl->id)->where('favorite', 1)->count();
                    $likeCount = Favourite::where('video_id', $videoImageUrl->id)->where('is_like', 1)->count();
                    $favourite = Favourite::where('video_id', $videoImageUrl->id)->where('user_id', $user->id)->where('favorite', 1)->count();
                    $like = Favourite::where('video_id', $videoImageUrl->id)->where('user_id', $user->id)->where('is_like', 1)->count();
                    $videoImageUrl->user_id = isset($videoImageUrl->user) ? $videoImageUrl->user->id : null;
                    $videoImageUrl->image_url = isset($videoImageUrl->user->person) ? $videoImageUrl->user->person->image_url : null;
                    $videoImageUrl->favouriteCount = isset($favouriteCount) ? $favouriteCount : null;
                    $videoImageUrl->likeCount = isset($likeCount) ? $likeCount : null;
                    $videoImageUrl->favourite = isset($favourite) ? $favourite : null;
                    $videoImageUrl->like = isset($like) ? $like : null;
                });


            $videoCollection = [];
            foreach ($videos as $key => $video) {
                $videoObj = [
                    'id' => $video->id,
                    'title' => $video->title,
                    'url' => $video->url,
                    'thumbnail' => $video->thumbnail,
                    'video_counts' => $video->views,
                    'star_counts' => $video->likeCount,
                    'heart_counts' => $video->favouriteCount,
                    'status' => $video->video_status,
                    'admin_status' => $video->admin_status,
                    'view_count' => $video->views,
                    'video_type' => $video->video_type,
                    'created_at' => Carbon::parse($video->created_at)->format('M-d-Y'),

                ];
                array_push($videoCollection, $videoObj);
            }

            return response()->json([
                'status' => true,
                'message' => 'videos retrieved Successfully!!',
                'data' => $videoCollection,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong! Please try again later.'
            ], 200);
        }
    }


    /**
     *
     * Clients Favourite Videos
     * @return JsonResponse
     */
    public function myFavouriteVideo()
    {
        try {

            $user = Auth::user();

            if ($user->isClient()) {
                $videos = Favourite::whereNotNull('video_id')->where('favorite', 1)->where('user_id', $user->id)->get()
                    ->each(function ($videoImageUrl) use ($user) {
                        $favouriteCount = Favourite::where('video_id', $videoImageUrl->video_id)->where('favorite', 1)->count();
                        $likeCount = Favourite::where('video_id', $videoImageUrl->video_id)->where('is_like', 1)->count();
                        $videoImageUrl->favouriteCount = isset($favouriteCount) ? $favouriteCount : null;
                        $videoImageUrl->likeCount = isset($likeCount) ? $likeCount : null;
                    });


                $data = array();
                foreach ($videos as $key => $allItem) {
                    $videoObj = array(
                        "id" => $allItem->id,
                        "user_id" => $allItem->user_id,
                        "video_id" => $allItem->video_id,
                        'title' => isset($allItem->video->title) ? $allItem->video->title : null,
                        'url' => isset($allItem->video->url) ? $allItem->video->url : null,
                        'thumbnail' => isset($allItem->video->thumbnail) ? $allItem->video->thumbnail : null,
                        "quote_id" => $allItem->quote_id,
                        "content_type" => $allItem->content_type,
                        "watch_later" => $allItem->watch_later,
                        "is_star" => $allItem->is_like,
                        "is_heart" => $allItem->favorite,
                        "heart_counts" => $allItem->favouriteCount,
                        "star_counts" => $allItem->likeCount,
                        'admin_status' => isset($allItem->video->admin_status) ? $allItem->video->admin_status : null,
                        'video_type' => isset($allItem->video->video_type) ? $allItem->video->video_type : null,
                        'user' => [
                            'id' => isset($allItem->user->person) ? $allItem->user->id : null,
                            'imageUrl' => isset($allItem->user->person->image_url) ? $allItem->user->person->image_url : null,
                            'name' => isset($allItem->user->name) ? $allItem->user->name : null,
                        ],
                        "ago" => $allItem->created_at->diffForHumans(),
                        "created_at" => $allItem->created_at,
                        "updated_at" => $allItem->updtad_at
                    );
                    array_push($data, $videoObj);
                }

                return response()->json([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Served'
                ]);
            } else {
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

    /**
     * Advisor
     * @return JsonResponse
     */
    public function myPrivateVideos()
    {
        try {
            $user = Auth::user();

            $videos = Video::
            whereUserId($user->id)->
            where('video_status', 'private')->latest()->get()
                ->each(function ($videoImageUrl) use ($user) {
                    $favouriteCount = Favourite::where('video_id', $videoImageUrl->id)->where('favorite', 1)->count();
                    $likeCount = Favourite::where('video_id', $videoImageUrl->id)->where('is_like', 1)->count();
                    $favourite = Favourite::where('video_id', $videoImageUrl->id)->where('user_id', $user->id)->where('favorite', 1)->count();
                    $like = Favourite::where('video_id', $videoImageUrl->id)->where('user_id', $user->id)->where('is_like', 1)->count();
                    $videoImageUrl->user_id = isset($videoImageUrl->user) ? $videoImageUrl->user->id : null;
                    $videoImageUrl->image_url = isset($videoImageUrl->user->person) ? $videoImageUrl->user->person->image_url : null;
                    $videoImageUrl->favouriteCount = isset($favouriteCount) ? $favouriteCount : null;
                    $videoImageUrl->likeCount = isset($likeCount) ? $likeCount : null;
                    $videoImageUrl->favourite = isset($favourite) ? $favourite : null;
                    $videoImageUrl->like = isset($like) ? $like : null;
                });

            $videoCollection = [];
            foreach ($videos as $key => $video) {
                $videoObj = [
                    'id' => $video->id,
                    'title' => $video->title,
                    'url' => $video->url,
                    'thumbnail' => $video->thumbnail,
                    'video_counts' => $video->views,
                    'star_counts' => $video->likeCount,
                    'heart_counts' => $video->favouriteCount,
                    'status' => $video->video_status,
                    'admin_status' => $video->admin_status,
                    'view_count' => $video->views,
                    'created_at' => Carbon::parse($video->created_at)->format('M-d-Y'),
                    'video_type' => $video->video_type
                ];
                array_push($videoCollection, $videoObj);
            }

            return response()->json([
                'status' => true,
                'message' => 'Videos retrieved Successfully!!',
                'data' => $videoCollection,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong! Please try again later.'
            ], 200);
        }
    }

    /**
     * client add watch later video status
     * @return JsonResponse
     */

    public function myWatchLaterVideo()
    {
        try {

            $user = Auth::user();

            if ($user->isClient()) {
                $videos = Favourite::whereNotNull('video_id')->where('watch_later', 1)->where('user_id', $user->id)->get()
                    ->each(function ($videoImageUrl) use ($user) {
                        $favouriteCount = Favourite::where('video_id', $videoImageUrl->video_id)->where('favorite', 1)->count();
                        $likeCount = Favourite::where('video_id', $videoImageUrl->video_id)->where('is_like', 1)->count();
                        $videoImageUrl->favouriteCount = isset($favouriteCount) ? $favouriteCount : null;
                        $videoImageUrl->likeCount = isset($likeCount) ? $likeCount : null;
                    });


                $data = array();
                foreach ($videos as $key => $allItem) {
                    $videoObj = array(
                        "id" => $allItem->id,
                        "user_id" => $allItem->user_id,
                        "video_id" => $allItem->video_id,
                        'title' => isset($allItem->video->title) ? $allItem->video->title : null,
                        'url' => isset($allItem->video->url) ? $allItem->video->url : null,
                        'thumbnail' => isset($allItem->video->thumbnail) ? $allItem->video->thumbnail : null,
                        "quote_id" => $allItem->quote_id,
                        "content_type" => $allItem->content_type,
                        "watch_later" => $allItem->watch_later,
                        "is_star" => $allItem->is_like,
                        "is_heart" => $allItem->favorite,
                        "heart_counts" => $allItem->favouriteCount,
                        "star_counts" => $allItem->likeCount,
                        'admin_status' => isset($allItem->video->admin_status) ? $allItem->video->admin_status : null,
                        'status' => isset($allItem->video->video_status) ? $allItem->video->video_status : null,
                        'video_type' => isset($allItem->video->video_type) ? $allItem->video->video_type : null,
                        'user' => [
                            'id' => isset($allItem->user->person) ? $allItem->user->id : null,
                            'imageUrl' => isset($allItem->user->person->image_url) ? $allItem->user->person->image_url : null,
                            'name' => isset($allItem->user->name) ? $allItem->user->name : null,
                        ],
                        "ago" => $allItem->created_at->diffForHumans(),
                        "created_at" => $allItem->created_at,
                        "updated_at" => $allItem->updtad_at
                    );
                    array_push($data, $videoObj);
                }

                return response()->json([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Served'
                ]);
            } else {
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

    /**
     * @param Request $request
     * @return JsonResponse
     * add views of video
     */
    public function videoView(Request $request)
    {
        try {
            $user = Auth::user();
            $video = Video::find($request->get('video_id'));
            $view = VideoView::where('user_id', $user->id)->where('video_id', $video->id)->first();

            if (isset($view) && !is_null($view)) {
                if (isset($video) && !is_null($video)) {

                    return response()->json([
                        'status' => true,
                        'message' => "Video already watched"
                    ]);
                }
            } else {
                if (isset($video) && !is_null($video)) {
                    VideoView::create([
                        'user_id' => $user->id,
                        'video_id' => $video->id,
                    ]);

                    $video->increment('views');

                    return response()->json([
                        'status' => true,
                        'message' => "Views Updated"
                    ]);
                }
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
     * @return JsonResponse
     * Top 10 Trending Videos
     */
    public function trendingVideo()
    {
        try {

            $trendingVideos = Video::where('video_status', 'publish')->orderBy('views', 'Desc')->take(10)->get()
                ->each(function ($videoImageUrl) {
                    $favouriteCount = Favourite::where('video_id', $videoImageUrl->id)->where('favorite', 1)->count();
                    $likeCount = Favourite::where('video_id', $videoImageUrl->id)->where('is_like', 1)->count();
                    $favourite = Favourite::where('video_id', $videoImageUrl->id)->where('favorite', 1)->count();
                    $like = Favourite::where('video_id', $videoImageUrl->id)->where('is_like', 1)->count();
                    $videoImageUrl->image_url = isset($videoImageUrl->user->person) ? $videoImageUrl->user->person->image_url : null;
                    $videoImageUrl->favouriteCount = isset($favouriteCount) ? $favouriteCount : null;
                    $videoImageUrl->likeCount = isset($likeCount) ? $likeCount : null;
                    $videoImageUrl->favourite = isset($favourite) ? $favourite : null;
                    $videoImageUrl->like = isset($like) ? $like : null;
                });
            $data = array();
            foreach ($trendingVideos as $trendingVideo) {
                $videoObj = array(
                    'id' => $trendingVideo->id,
                    'type' => "Video",
                    'video' => [
                        'title' => $trendingVideo->title,
                        'url' => $trendingVideo->url,
                        'thumbnail' => $trendingVideo->thumbnail,
                        'star_counts' => $trendingVideo->likeCount,
                        'heart_counts' => $trendingVideo->favouriteCount,
                        'is_heart' => $trendingVideo->favourite,
                        'is_star' => $trendingVideo->like,
                        'video_type' => $trendingVideo->video_type,
                        'views' => $trendingVideo->views,
                    ],
                    'quote' => null,
                    'created_at' => $trendingVideo->created_at,
                );
                array_push($data, $videoObj);
            }
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => "views of this year"
            ]);



        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }

    }

}