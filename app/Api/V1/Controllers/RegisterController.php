<?php

namespace App\Api\V1\Controllers;

use App\Advisorrequest;
use App\Clientrequest;
use App\Jobs\SendWelcomeEmailToClientJob;
use App\Mail\WelcomeClient;
use App\Models\Person;
use App\User;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\RegisterRequest;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelRoles\Models\Role;


class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'min:8|required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',

            ], [
                'email.required' => 'The email is required.',
                'email.unique' => 'An account with this email address already exists. Please login with your email and password',
                'name.required' => 'Name is required',
                'password.required' => 'Password is required',
                'password.regex' => 'Minimum 8 characters, at least one uppercase letter, one lowercase letter, one number and one special character',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => $validator->getMessageBag()->first(),
                ], 200);
            }
            $name = explode(' ', $request->get('name'));

            if($request->get('role') == 'client') $role = Role::where('slug', 'client')->first();
            else $role = Role::where('slug', 'advisor')->first();



            $userData = [
                'name' => $request->get('name'),
                'password' => $request->get('password'),
                'email' => $request->get('email'),
                'term_condition' => 1
            ];


        if($userData){

            $user = User::create($userData);

            $personData = [
                'first_name' => isset($name[0]) ? $name[0]: $name,
                'last_name' => isset($name[1]) ? $name[1]: '',
                'email' => $request->get('email'),
                'user_id' => $user->id,
            ];
            $person = \App\Person::create($personData);

            $chatData = $this->registerChat($user);

            // Start
            // This portion of the code will be removed later.
            $user->chat_id = isset($chatData['_id']) ? $chatData['_id'] : '';
            $user->chat_email = isset($chatData['email']) ? $chatData['email'] : '';
            $user->save();
            // End

            if(!is_null($role)) {
                $user->attachRole($role->id);
            }


            $requestData = [
                'user_id' => $user->id,
                'status' => 'pending',
                'request_date' => Carbon::now(),
            ];
            if($request->get('role') == 'client'){
                $requestData[] = ['client_type' => ''];
                $client_request = Clientrequest::create($requestData);
            }  else{
                $advisor_request = Advisorrequest::create($requestData);
            }

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();

            /*$link=url('/'.'activateclient/'.base64_encode($user->id));
            Mail::to($user->email)->send(new WelcomeClient($user,$link));*/


            /* Email & Notification Section for Successful signup/onbooarding message */
            SendWelcomeEmailToClientJob::dispatch($user)->delay(now()->addMinutes(1));

            if ($tokenResult) {
                $user->no_of_logins = $user->no_of_logins + 1;
                $user->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully Logged in.',
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_questionare_completed' => $user->is_questionare_completed,
                        'chat_id' => $user->chat_id,
                        'chat_email' => $user->chat_email,
                        'status' => $user->status,
                        'activated' => $user->activated,
                        'first_login' => 1,
                        'is_rate_card' => 0,

                    ],
                    'access_token' => $tokenResult->accessToken,
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something went wrong!',
            ], 200);
        }

        /*$role = Role::where('slug', $request->type)->first();
        if(!is_null($role)) {
            $advisor->attachRole($role->id);
        }
        SendWelcomeEmailToClientJob::dispatch($advisor)->delay(now()->addMinutes(1));*/

        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'Advisor request received. We are on it.',
        ], 200);

        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong! Please try again later.'
            ],200);
        }
    }

    function registerChat($user){

        try {
            $postRequest = array(
                "name" => $user->name,
                "email" => $user->email,
                "phone" => '321',
                "user" => $user->id,
                "image" => is_null($user->image_url) ? 'picsum/photos/200' : $user->image_url,
                "status" => "new",
                "project" => 'guidefi',
                "token" => "tesphoto5e79f60712c51407fac65baesstews"
            );

            $fields_string = '';
            foreach($postRequest as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            $chat_url = config('app.chat_url', 'https://chat.guidefi.com/');// Config::get('constants.CHAT_URL')

            Log::info('******************************CHAT_URL***********************************');
            Log::info($chat_url);
            Log::info('******************************CHAT_URL***********************************');
//            $cURLConnection = curl_init('https://c16bbad98cf4.ngrok.io/v1/users');

            $headers = [
                'authorization: d9rZIeLOS4fktZ0R/7X2gpzMsrWSupjeqaLK1CzsMhLVFFYdJS3KyIKpT0myk8m9',
            ];

            $cURLConnection = curl_init($chat_url.'v1/users');
            curl_setopt($cURLConnection, CURLOPT_POST,           true);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);

            $data =  json_decode($apiResponse, true);

            return $data;
        }catch (Exception $e){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ],200);
        }

    }

}
