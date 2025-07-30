<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\ResetPassword;
use App\Http\Requests\NewPassword;
use App\Http\Requests\ResetPasswordApi;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        return view('auth.passwords.resetemail');
    }
    public function showapplinks()
    {
        return view('auth.passwords.showapplinks');
    }
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function resetpasswordapi($token)
    {
        $string = explode('|', base64_decode($token));
        $email = $string[0];
        $time = $string[1];
        $userType = $string[3];

        $userinfo = User::where('email',$email)->first();
        //dd($user);
        return view('auth.passwords.newpasswordapi',compact('userinfo','userType'));
    }
    public function savepasswordapi(NewPassword $request)
    {
        // $userinfo = User::where('id',$request->input('userid'))->first();
        // $userinfo->password=$request->input('password');
        // $userinfo->save();
        $userinfo=User::where('token',$request->input('token'))->first();
        $userinfo->password=$request->input('password');
        $userinfo->save();
        return redirect('showapplinks')->with('user_name', $userinfo->name);
    }

    public function resetlink(Request $request, $token)
    {
        $userinfo=User::where('token',$token)->first();
        if(is_null($userinfo))
        {
            return redirect('resetpassword')->with('error', "Token Expired");
        }else{
            return view("auth.passwords.newpassword", compact("userinfo"));
        }
    }

    public function savepassword(NewPassword $request)
    {
        $userinfo=User::where('token',$request->input('token'))->first();
        $userinfo->password=$request->input('password');
        $userinfo->save();
        return redirect('login')->with('success', "New Password Has Been Updated ");

    }
    public function emailverification(ResetPassword $request)
    {
     $userexist=User::where('email', '=', $request->input('email'))->with('roles')->first();
        // dd($userexist);
        // dd($userexist->roles[0]->slug);
     if(is_null($userexist))
     {
         return redirect('resetpassword')->with('error', "Email Address Not found in Our Database");
     }

    if (!is_null($userexist->roles)){
        if($userexist->roles[0]->slug=='client' || $userexist->roles[0]->slug=='advisor')
        {
            return redirect('resetpassword')->with('error', "Account does not exist.");
        } else {
            $token = str_random(64);
            $userexist->token=$token;
            $userexist->save();
            $mdata = ['token'  => $token,];
            try{
                $mail_status = Mail::to($userexist->email)->send(new ForgotPassword($userexist,$token));
                //If error from Mail::send
                if(!is_null($mail_status) && $mail_status->failures() > 0 ){
                    //Fail for which email address...
                    foreach(Mail::failures as $address) {
                        print $address . ', ';
                    }
                    exit;
                }
            }
            catch(Exception $e){
                // Get error here
                print $e->getMessage();
                exit;
            }
                 return redirect('resetpassword')->with('success', "Reset Password Link Sent into Your Email ");
        }
    }
    }
}
