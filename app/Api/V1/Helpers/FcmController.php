<?php

/**
 * class : FcmController
 * By : Asif Hussain
 * Date : 05 May, 2020
 */

namespace App\Api\V1\Helpers;


use App\Models\Notification;
use Dingo\Api\Auth\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class FcmController
{

    public $serverkey;

    /**
     * FcmController constructor.
     * @param $serverkey: Firebase cloud server key
     */
    public function __construct($serverkey)
    {
        $this->serverkey = $serverkey;
    }

    /**
     * Push Notification across android and ios
     * @param $title: title of notification
     * @param $description: body of notification
     * @param $userId: user id etc 86
     * @return JsonResponseF
     */
    public function pushNotification($title, $description, $type, $userId)
    {
        $user = \Illuminate\Support\Facades\Auth::user() ;
        $role = (isset($user->roles) && !is_null($user->roles)) ? $user->roles->first()->name : null ;
        $sender =  (isset($user->id) && !is_null($user->roles)) ? $user->id : null ;

        $notificatioData = ['title'=> $title,'description'=>$description,'type'=>$type,'sender_id'=>$sender,'receiver_id'=>$userId,'role'=>$role];
        Notification::create($notificatioData);
        $responseAndroid = $this->fcmAndroid($title, $description, $type, $userId);
        $responseiOS = $this->fcmiOS($title, $description, $type, $userId);
        return response()->json(["success" => true, "responseAndroid" => $responseAndroid, "responseiOS" => $responseiOS], 200);
    }

    /**
     * Boilerplate code of push notification on android devices
     * @param $title
     * @param $description
     * @param $userId
     * @return mixed
     */
    public function fcmAndroid($title, $description, $type, $userId)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            "to" => '/topics/guidefi_android_live_' . $userId,
            "data" => [
                "push_type" => "app",
                "payload" => [
                    "title" => $title,
                    "description" => $description,
                    "type" => $type
                ]
            ]

        ];

        $headers = [
            'Authorization: Bearer '.$this->serverkey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * Boilerplate code of push notification on ios devices
     * @param $title
     * @param $description
     * @param $userId
     * @return mixed
     */
    public function fcmiOS($title, $description, $type, $userId)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            "to" => '/topics/guidefi_ios_live_' . $userId,
            "notification" => [
                "title" => $title,
                "body" => $description,
                "sound" => "default"
            ],
            "data" => [
                "push_type" => "app",
                "payload" => [
                    "title" => $title,
                    "description" => $description,
                    "type" => $type
                ]
            ]

        ];

        $headers = [
            'Authorization: Bearer '.$this->serverkey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

}
