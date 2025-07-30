<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Mail\ForgotPasswordApi;
use App\User;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Api\V1\Requests\ForgotPasswordRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ForgotPasswordController extends Controller
{
    public function sendResetEmail(Request $request)
    {
        try {
            $email = $request->only('email');
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Sorry the email address you entered does not exist.',
                ], 200);
            }
//            $isTherapist = $request->get('isTherapist');
//            $isClient = $request->get('isClient');
            $userType = strtolower($request->get('role'));

            //$user1 =User::where('email', $email)->first();
//            \DB::connection()->enableQueryLog();
            if ($userType == 'advisor'){
                $user1 = User::where('email', $email)->whereHas('roles', function ($q) use ($userType) {
                    $q->where('slug', "Advisor");
                })->first();
            }else{
                $user1 = User::where('email', $email)->whereHas('roles', function ($q) use ($userType) {
                    $q->where('slug', "CLient");
                })->first();
            }


//            $queries = \DB::getQueryLog($user1);


//            dd($user1);
            if (is_null($user1)) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Account does not exist.',
                ], 200);
            }

//            if (!$user->activated) {
//                return response()->json([
//                    'status' => false,
//                    'data' => null,
//                    'message' => 'Account is not active, Please contact admin for this.',
//                ], 200);
//            }

            $mytime = Carbon::now();/*->addMinutes(env('PASSWORD_RESET_EMAIL_TIME'))*/

            $string = base64_encode($request->get('email') . '|' . $mytime->toDateTimeString() . '|' . $user->id . '|' . $userType);
            $link = url('/api/auth/verify/' . $string);

            /* Email & Notification Section for Password reset */
            Mail::to($user->email)->send(new ForgotPasswordApi($user, $link));

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'We have emailed you a password reset link',
            ], 200);
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something went wrong, Please try again later!',
            ], 200);
        }

    }

    public function verifyString($token)
    {
        try {
            $string = explode('|', base64_decode($token));

            $email = $string[0];
            $time = $string[1];
            $userType = $string[3];

            $user = User::where('email', $email)->whereHas('roles', function ($q) use ($userType) {
                $q->where('slug', $userType);
            })->first();
            if (is_null($user)) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Account does not exist.',
                ], 200);
            }
//            if (!$user->activated) {
//                return response()->json([
//                    'status' => false,
//                    'data' => null,
//                    'message' => 'Account is not active, Please contact admin for this.',
//                ], 200);
//            }

            if ($user->hasRole($userType)) {
                $emailShootTime = Carbon::parse($time);
                $curTime = Carbon::now();

                if ($user && $emailShootTime->diffInMinutes($curTime) <= Config::get('settings.PASSWORD_RESET_EMAIL_TIME')) {
                    $mytime = Carbon::now();
                    $string = base64_encode($email . '|' . $mytime->toDateTimeString() . '|' . $user->id . '|' . $userType);
                    //$link = url('/api/auth/'.$userType.'/resetCustom?token='.$string);
                    //$link = url('/api/auth/'.$userType.'/resetPassword?token='.$string);
//                    $link = 'https://admin.ayanatherapy.com/api/auth/therapist/resetCustom?token='.$string;
                    $link = url('/resetpasswordapi/' . $string);

                    return redirect($link);
                } else {
                    return redirect('404');
                }
            }
            return response()->json([
                'status' => 'ok',
            ]);
        } catch (ErrorException $exception) {
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something went wrong, Please try again later!',
            ], 200);
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return PasswordBroker
     */
    private function getPasswordBroker()
    {
        return Password::broker();
    }
}
