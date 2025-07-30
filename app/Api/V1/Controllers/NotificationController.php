<?php


namespace App\Api\V1\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Notification;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mockery\Matcher\Not;

class NotificationController extends Controller
{
    public function index(){
        $userId = Auth::user()->id;
        $notifications = Notification::where('receiver_id',$userId)->get();
        $notificationCollection = [];
        foreach ($notifications as $key => $notification) {
            $notificationObj = [
                'id' => $notification->id,
                'title' => $notification->title,
                'description' => $notification->description,
                'sender' => !is_null($notification->user)?$notification->user->name:'',
                'type' => $notification->type,
                'role' => (!is_null($notification->user) &&!is_null($notification->user->roles))?$notification->user->roles->first()->name:'',
                'created_at' => Carbon::parse($notification->created_at)->format('M-d-Y'),
            ];
            array_push($notificationCollection, $notificationObj);
        }
        return response()->json([
            'notifications'=>$notificationCollection,
            'status' => true,
            'message' => 'Notification retrieved Successfully!!'
        ]);

    }

    public function destroy(Request $request){
        try {
            $notify = Notification::whereId($request->notification_id)->delete();
            if($notify){
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Notification Deleted Successfully.'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'This notification id is not exists.'
                ]);
            }
            
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong! Please try again later.'
            ]);
        }

    }

}