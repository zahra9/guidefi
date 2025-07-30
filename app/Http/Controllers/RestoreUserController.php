<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\InviteEmployee;
use App\Models\Profile;
use App\Mail\CorporateUsersMail;
use App\Mail\CUsersMail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\UserCreate;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\EmployeeCreate;
use jeremykenedy\LaravelRoles\Models\Role;
use App\Traits\CaptureIpTrait;
use App\CorporateClient;
use App\CorporateClientSignupCode;



class RestoreUserController extends ProfilesController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * User Account Restore.
     *
     * @param Request $request
     * @param string                   $token
     *
     * @return Response
     */
    public function newuserrequest($id)
    {

        $created_by=base64_decode($id);
        $isCorpEmail=CorporateClient::where('user_id',$created_by)->select('is_corp_email','id')->first();

        $success='';
        return View('usersmanagement.new-employee', compact('success','created_by','isCorpEmail'));

    }
    public function saveemployee(EmployeeCreate $request)
    {
        // code for get corporate user email
        $corporateuser=User::where('id',$request->input('created_by'))->select('email')->first();
        // code for get corporate user email domain
        $cemail=explode('@',$corporateuser->email);
        // code for get requested user email domain
        $useremail=explode('@',$request->input('email'));
        // code for check if no domain match of emplyee and corporate users
        if($cemail[1]!==$useremail[1]){
            return back()->with(array('error'=> 'Your are Not Authorized To Submit Request','fullname'=>$request->input('fullname'),'email'=>$request->input('email')));
        }

        $roles = Role::where('slug','client')->first();
        $ipAddress = new CaptureIpTrait();
        $profile = new Profile();
        $random_password = mt_rand(100000,999999);
        // dd($request->all());
        $user = new User();
        $user->name=$request->input('fullname');
        $user->first_name=Null;
        $user->last_name=Null;
        $user->activated=1;
        $user->admin_ip_address=$ipAddress->getClientIp();
        $user->email=$request->input('email');
        $user->password=$random_password ;
        $user->created_by=$request->input('created_by');
        $user->save();
        $user->profile()->save($profile);
        $user->attachRole($roles->id);
        $mdata = [
            'username'  => $user->email,
            'password'   => $random_password,
            'createdby'   => ''

        ];

        try{
            $mail_status = Mail::to($user->email)->send(new CUsersMail($mdata));
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
        $createdby=Crypt::encryptString($request->input('created_by'));
        //return view('newuser')->with('success', 'Your Request has been Submitted');
        return back()->with('success', 'Your Request has been Submitted');
    }
    public function saveEmployeeWithoutDomain(EmployeeCreate $request)
    {
        // code for get corporate user email
        $corporateuser=User::where('id',$request->input('created_by'))->select('email')->first();
        // code for get corporate user email domain
        $cemail=explode('@',$corporateuser->email);
        // code for get requested user email domain
        $useremail=explode('@',$request->input('email'));
        // code for check if no domain match of emplyee and corporate users
       // Query table corp_cli_ident_signup_code
       // Where is_used=0 and sign_up_code="" and corp_client_id=95

       /* if($cemail[1]!==$useremail[1]){
            return back()->with(array('error'=> 'Your are Not Authorized To Submit Request','fullname'=>$request->input('fullname'),'email'=>$request->input('email')));

        }*/
        $checkocde=CorporateClientSignupCode::where('is_used',0)->where('email',$request->input('email'))->whereNull('user_id')->where('sign_up_code',$request->input('sign_up_code'))->where('corp_client_id',$request->input('created_by'))->first();

        if(is_null($checkocde)){
            return back()->with(array('error'=> 'Please Enter Valid email and code for signup','fullname'=>$request->input('fullname'),'email'=>$request->input('email')));
        }

        $roles = Role::where('slug','client')->first();
        $ipAddress = new CaptureIpTrait();
        $profile = new Profile();
        $random_password = mt_rand(100000,999999);
        // dd($request->all());
        $user = new User();
        $user->name=$request->input('fullname');
        $user->first_name=Null;
        $user->last_name=Null;
        $user->activated=1;
        $user->admin_ip_address=$ipAddress->getClientIp();
        $user->email=$request->input('email');
        $user->password=$random_password ;
        $user->created_by=$request->input('created_by');
        $user->save();

        if(!is_null($checkocde)){
            $checkocde->is_used=1;
            $checkocde->user_id=$user->id;
            $checkocde->save();
        }

        $user->profile()->save($profile);
        $user->attachRole($roles->id);

        $mdata = [
            'username'  => $user->email,
            'password'   => $random_password,
            'createdby'   => ''

        ];

        try{
            $mail_status = Mail::to($user->email)->send(new CUsersMail($mdata));
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
        $createdby=Crypt::encryptString($request->input('created_by'));
        //return view('newuser')->with('success', 'Your Request has been Submitted');
        return back()->with('success', 'Your Request has been Submitted');
    }
    public function userReActivate(Request $request, $token)
    {
        $userKeys = new ProfilesController();
        $sepKey = $userKeys->getSeperationKey();
        $userIdKey = $userKeys->getIdMultiKey();
        $restoreKey = config('settings.restoreKey');
        $encrypter = config('settings.restoreUserEncType');
        $level5 = base64_decode($token);
        $level4 = openssl_decrypt($level5, $encrypter, $restoreKey);
        $level3 = base64_decode($level4);
        $level2 = urldecode($level3);
        $level1[] = explode($sepKey, $level2);
        $uuid = $level1[0][0];
        $userId = $level1[0][1] / $userIdKey;
        $user = SoftDeletesController::getDeletedUser($userId);

        if (!is_object($user)) {
            abort(500);
        }

        $deletedDate = $user->deleted_at;
        $currentDate = Carbon::now();
        $daysDeleted = $currentDate->diffInDays($deletedDate);
        $cutoffDays = config('settings.restoreUserCutoff');

        if ($daysDeleted >= $cutoffDays) {
            return redirect('/login')->with('error', trans('profile.errorRestoreUserTime'));
        }

        $user->restore();

        return redirect('/login')->with('success', trans('profile.successUserRestore', ['username' => $user->name]));
    }
}
