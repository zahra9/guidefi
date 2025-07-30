<?php

namespace App\Http\Controllers;

use App\Favourite;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use Carbon\Carbon;
use Dawson\Youtube\Facades\Youtube;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Filesystem\Filesystem;
use Image;
use Lakshmaji\Thumbnail\Thumbnail;
//use Storage;
use FFMpeg;
//use Pbmedia\LaravelFFMpeg\FFMpeg;
//use Pbmedia\LaravelFFMpeg\FFMpeg;
use Illuminate\Support\Facades\Storage;
class VideoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()

    {
        $videos = Video::latest()->get();
        $privacyStatus = Config::get('constants.PRIVACY_TYPE') ? Config::get('constants.PRIVACY_TYPE') : [];
        return view('video.index', compact('videos', 'privacyStatus'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('video.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(VideoRequest $request)
    {
      try {
        $image = $request->file('video');
          set_time_limit(0);
        // create fmpeg decoding binaries
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
            $fileAws = Storage::disk('s3')->put($fileName, file_get_contents($fileName), 'public');
            unlink($fileName);

        }

        else{
            $fileName = 'videos-s3' . time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put($fileName, file_get_contents($image), 'public');
        }

      //  upload video on aws
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
            'message' => "Video uploaded Successfully!!",
        ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' =>$e->getMessage(),
            ]);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
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
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $video = Video::find($id);
            if ($video) {
                $title = $video->title;
//                Youtube::delete($video->video_id);
                $video->delete();
                return response()->json([
                    'status' => true,
                    'message' => $title . ' deleted successfully!'
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateVideoStatus(Request $request, $id)
    {
        $video = Video::find($id);
        $status = $request->status;
        if ($video) {
            if($status == 'approved'){
                $videoArray = ['admin_status' => $status,'video_status'=>'publish'];
            }else{
                $videoArray =['admin_status' => $status,'video_status'=>'private'];
            }
            if ($video->update($videoArray)) {
                return response()->json(['code' => 1,
                    'status' => $request->status,
                    'message' => 'Operation Successful !']);
            }
            // ****youtube video privacy status changed*****

            /*$video = Youtube::update($video->video_id, [
                  'title' => $video->title,
                  'category_id' => 10
              ], $privacy);*/
        }
    }


}
