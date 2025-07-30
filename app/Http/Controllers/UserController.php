<?php

namespace App\Http\Controllers;

use App\Advisorrequest;
use App\Api\V1\Helpers\StripeHelper;
use App\AppointmentAadvisorClient;
use App\CorpClientInvLog;
use App\CorporateClientInvoice;
use App\CorporateClientPrepaidPayment;
use App\CorporateClientSignupCode;
use App\Exports\DashboardExport;
use App\Mail\CorpUsersInvEmail;
use App\Mail\NonCorpUsersInvEmail;
use App\Models\Notification;
use App\StripePaymentLog;
use Auth;
use App\Therapist;
use App\User;
use App\SubscriptionPlan;
use App\CorporateClient;
use App\Answers;
use App\Payment;
use App\Appointment;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use jeremykenedy\LaravelRoles\Models\Role;
use Illuminate\Support\Facades\DB;
use App\ClientSelectedTherapist;
use App\Therapistrequest;
use Illuminate\Http\Request;
use App\Http\Requests\InviteEmployee;
use App\Traits\CaptureIpTrait;
use App\Models\Profile;
use App\Mail\CorporateUsersMail;
use App\Mail\CUsersMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\UserCreate;
use App\Http\Requests\UploadFile;
use App\Imports\ExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\EmployeeCreate;
use phpDocumentor\Reflection\Types\Null_;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {

        $user = Auth::user();
        if ($user->isClient() || $user->isTherapist() || $user->isAdvisor()) {
            Auth::logout();
            return redirect('login')->with('error', "Account not found");
        }
        $user->no_of_logins = $user->no_of_logins + 1;
        $user->save();
        if ($user->isAdmin()) {

            //******yearly profit********

            $yearlyProfit = StripePaymentLog::
            whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('name', 'Advisor');
                });
            })->whereYear('created', '=', Carbon::now()->year)
                ->sum('amount_paid');
            // ******monthly profit******

            $monthlyProfit = StripePaymentLog::whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('name', 'Advisor');
                });
            })->where(
                'created', '>=', Carbon::now()->startOfMonth()->subMonth()->toDateString()
            )->sum('amount_paid');


                //******weekly profit********

            $date = \Carbon\Carbon::today()->subDays(7);
            $weeklyProfit = StripePaymentLog::
            whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('name', 'Advisor');
                });
            })->where('created', '>=', $date)
                ->sum('amount_paid');


            //******daily profit********

            $dailyProfit = StripePaymentLog::
            whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('name', 'Advisor');
                });
            })->where('created', Carbon::now())
                ->sum('amount_paid');
            //******revenue Total********
            $revenueTotal = StripePaymentLog::whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('name','!=', 'Advisor');
                });
            })->sum('amount_paid');

            //******revenue Yearly********

            $revenueYearly = StripePaymentLog::
            whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('name','!=', 'Advisor');
                });
            })->
            whereYear('created', '=', Carbon::now()->year)->sum('amount_paid');

            //******revenue Month********

            $revenueMonth = StripePaymentLog::
            whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('name','!=', 'Advisor');
                });
            })->where('created', '>=', Carbon::now()->startOfMonth()->subMonth()->toDateString())->sum('amount_paid');

            //******revenue Month********

            $revenueMonth = StripePaymentLog::
            whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('name','!=', 'Advisor');
                });
            })->
            where('created', '>=', Carbon::now()->startOfMonth()->subMonth()->toDateString())->sum('amount_paid');

            //******weekly Revenue********

            $date = \Carbon\Carbon::today()->subDays(7);
            $weeklyRevenue = StripePaymentLog::
            whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('name', 'Advisor');
                });

            })->where('created', '>=', $date)->sum('amount_paid');
            //******total Profit********

            $profitTotal = StripePaymentLog::whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q) {
                    $q->where('name', 'Advisor');
                });
            })->sum('amount_paid');
            $revenues  = collect([$revenueTotal,$revenueYearly,$revenueMonth,$weeklyRevenue]);
            $profit  = collect([$profitTotal,$yearlyProfit,$monthlyProfit,$weeklyProfit]);

            $total_users = User::whereHas('roles', function ($q) {
                $q->where('name','!=', 'Admin');
            })->count();
            $total_advisors = User::whereHas('roles', function ($q) {
                $q->where('name', 'Advisor');
            })->count();

            $total_clients = User::whereHas('roles', function ($q) {
                $q->where('name', 'Client');
            })->count();

            // ****** max value btw advisor and client and calc with 10 percent fr
            $maxcollection = (!empty($total_advisors) && !empty($total_clients)) ?max([$total_advisors, $total_clients]):0;
            $addTenPercent = ($maxcollection) * 0.15;
            $sumAdvisorClient = normalPrettyPrice($maxcollection + $addTenPercent);

            $unPaid = StripePaymentLog::where('paid',0)->count();
            $paid = StripePaymentLog::where('paid',1)->count();
            $completedCalls = AppointmentAadvisorClient::where('call_status',1)->count();
            $waitingCalls = AppointmentAadvisorClient::where('call_status',0)->count(
            );

            // ****** total client 6 months stats
            $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'Client');
            })->select('id', 'created_at')->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });

            $usermcount = [];
            $userArr = [];

            foreach ($users as $key => $value) {
                $usermcount[(int)$key] = count($value);
            }
            for($i = 1; $i <= 12; $i++){
                if(!empty($usermcount[$i])){
                    $userArr[$i] = $usermcount[$i];
                }else{
                    $userArr[$i] = 0;
                }
            }

            $clientLastMonth = collect($userArr)->reverse()->take(6);//->take(6);

            // ****** total advisor 6 months stats
            $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'Advisor');
            })->select('id', 'created_at')->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });

            $usermcount = [];
            $userArr = [];

            foreach ($users as $key => $value) {
                $usermcount[(int)$key] = count($value);
            }
            for($i = 1; $i <= 12; $i++){
                if(!empty($usermcount[$i])){
                    $userArr[$i] = $usermcount[$i];
                }else{
                    $userArr[$i] = 0;
                }
            }

            $advisorLastMonth = collect($userArr)->reverse()->take(6);

            return view('pages.admin.home',compact('sumAdvisorClient','advisorLastMonth','clientLastMonth','profit','revenues','dailyProfit','weeklyProfit','yearlyProfit','monthlyProfit','revenueYearly','revenueMonth','total_users','total_advisors','total_clients','unPaid','paid','completedCalls','waitingCalls'));
        }


        return view('pages.user.home');
    }

    private function getOverDueBalance($user)
    {
        $overDue = CorporateClientPrepaidPayment::where('user_id', $user->id)->sum('total_amount') - CorporateClientInvoice::where('corp_client_id', $user->id)/*->where('payment_status', 'paid')*/ ->sum('invoice_total');

        return $overDue;
    }

    public function updateProfile(Request $request)
    {

        try {
            $user = User::where('id', $request->get('user_id'))->first();

            if (!is_null($user->therapist)) {
                $this->validate($request, [
                    'about' => 'required',
                ]);

                $therapist = $user->therapist;
                $therapistInput = $request->only('about');

                $therapist->update($therapistInput);

            }

            return redirect('/therapists/' . $user->therapist->id)->with('success', 'Profile Bio updated successfully!');

        } catch (ErrorException $exception) {
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong! Please, try again later.'
            ]);
        }

    }

    public function updateUserImage(Request $request)
    {

        try {
            $user = User::where('id', $request->get('therapist'))->first();
            $bbImageKey = "dc791ba1ef8c9e99745fa708784ab99b";

            $image = $request->file('image');
            $image = base64_encode(file_get_contents($request->file('image')));

            $payload = ['image' => $image];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key=' . $bbImageKey);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $content = curl_exec($ch);
            $data = json_decode($content, true);


            $userInput['image_url'] = $data['data']['display_url'];

            if ($user->therapist) {
                $user->therapist()
                    ->update(['image_url' => $data['data']['display_url']
                    ]);
            }

//            Therapist::where('user_id', $user->id)->update([ 'image_url'=> $data['data']['display_url'] ]);

            $user->update($userInput);

            curl_close($ch);

            return redirect('/therapists/' . $user->therapist->id)->with('success', 'Profile picture updated successfully');

        } catch (ErrorException $exception) {
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong! Please, try again later.'
            ]);
        }

    }

    public function inviteemployee(Request $request)
    {

        try {
            $emails = explode(',', $request->get('email'));
            $currentUser = Auth::user();

            $isAdmin = 0;
            if ($currentUser->hasRole('admin')) {
                $isAdmin = 1;
                $currentUser = User::find($request->get('corporate'));
            }

            if(is_null($request->get('email'))){
                return response()->json((array('message' => 'No Email is provided.')), 200);
            }
            foreach ($emails as $key => $email) {

                // Check if it is a valid email.
                // if this email is not exist in the system
                // send invitation email and store into a table for record purpose also update with email send status.
                // send a signup code in this email so user can add that code at the time of signup.

                $user = User::where('email', $email)->first();
                if (!$user) {

                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                        $CorpClientInvLog = new CorpClientInvLog();
                        $CorpClientInvLog->corp_client_id = $currentUser->id;
                        $CorpClientInvLog->first_name = Null;
                        $CorpClientInvLog->last_name = Null;
                        $CorpClientInvLog->email = $email;
                        $CorpClientInvLog->invited_by_admin = $isAdmin;
                        $CorpClientInvLog->save();

                        Log::info("$email Email added and invitation email ready to send.");

                        $userid=base64_encode($currentUser->id);

                        $randomString = '';
                        $is_corp_email = false;
                        if(!$currentUser->corporateclient->is_corp_email){

                            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            $charactersLength = strlen($characters);

                            for ($j = 0; $j < 6; $j++) {
                                $randomString .= $characters[rand(0, $charactersLength - 1)];
                            }

                            $signupcode=new CorporateClientSignupCode();
                            $signupcode->corp_client_id=$currentUser->id;
                            $signupcode->is_used=0;
                            $signupcode->user_id=Null;
                            $signupcode->email=$email;
                            $signupcode->created_by=Auth::user()->id;
                            $signupcode->sign_up_code=$randomString ;
                            $signupcode->save();

                            $is_corp_email = true;
                        }

                        $mdata = [
                            'client' => 'User',
                            'invitation_link' => url('/newuser/'.$userid),
                            'is_corp_email' => $is_corp_email,
                            'company_name' => $currentUser->corporateclient->company_name,
                            'system_help_email' => env('system_help_email'),
                            'signup_code' => $randomString,
                        ];

                        if($currentUser->corporateclient->is_corp_email){
                            Mail::to($email)->send(new CorpUsersInvEmail($mdata));
                        }else{
                            Mail::to($email)->send(new NonCorpUsersInvEmail($mdata));
//                            Mail::to($email)->send(new CorpInvEmail($mdata));
                        }

                    } else {
                        Log::info("$email is not a valid email address");
                    }


                } else {
                    Log::info('user already exists');
                }
            }

            /*$currentUser = Auth::user();
            $roles = Role::where('slug', 'client')->first();
            $ipAddress = new CaptureIpTrait();
            $profile = new Profile();
            $random_password = mt_rand(100000, 999999);
            // dd($request->all());
            $user = new User();
            $user->name = $request->input('email');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->activated = 1;
            $user->admin_ip_address = $ipAddress->getClientIp();
            $user->email = $request->input('email');
            $user->password = $random_password;
            $user->created_by = $currentUser->id;
            $user->save();
            $user->profile()->save($profile);
            $user->attachRole($roles->id);*/

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Invitation to Employees Sent Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong! Please, try again later.'
            ]);
        }
    }
    /*
     * This method is removed from the form. We will update this, later after confirming this is really removed.
     * */
    public function admininviteemployee(InviteEmployee $request)
    {


        $currentUser = Auth::user();
        $roles = Role::where('slug', 'client')->first();
        $ipAddress = new CaptureIpTrait();
        $profile = new Profile();
        $random_password = mt_rand(100000, 999999);
        // dd($request->all());
        $user = new User();
        $user->name = $request->input('first_name');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->activated = 1;
        $user->admin_ip_address = $ipAddress->getClientIp();
        $user->email = $request->input('email');
        $user->password = $random_password;
        $user->created_by = $request->input('userid');
        $user->save();
        $user->profile()->save($profile);
        $user->attachRole($roles->id);

        $mdata = [
            'username' => $user->email,
            'password' => $random_password,
            'createdby' => $currentUser->name

        ];

        try {
            $mail_status = Mail::to($user->email)->send(new CUsersMail($mdata));
            //If error from Mail::send
            if (!is_null($mail_status) && $mail_status->failures() > 0) {
                //Fail for which email address...
                foreach (Mail::failures as $address) {
                    print $address . ', ';
                }
                exit;
            }

        } catch (Exception $e) {
            // Get error here
            print $e->getMessage();
            exit;
        }


        return response()->json((array('message' => 'Invitaion to Employee Send Successfully')), 200);


    }

    public function bulkimport(UploadFile $request)
    {
        $currentUser = Auth::user();
        $roles = Role::where('slug', 'client')->first();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
        $created_by = auth()->user()->id;
        $extension = $request->file('csvfile')->extension();
        if ($extension === 'txt' || $extension === 'csv') {
            $path = $request->file('csvfile')->getRealPath();
            $data = array_map('str_getcsv', file($path));
        } else {
            $usersarray = Excel::toArray(new ExcelImport, request()->file('csvfile'));
            $data = $usersarray[0];

        }

        //dd($data);
        for ($i = 1; $i < sizeof($data); $i++) {
            $userexist = User::where('email', $data[$i][2])->get();
            if (count($userexist) > 0) {
                $data[$i][3] = "User Already Exist";
                $data[$i][4] = "Email Already Sent";
            } else {

                $ipAddress = new CaptureIpTrait();
                $profile = new Profile();
                $random_password = mt_rand(100000, 999999);
                // dd($request->all());
                $user = new User();
                $user->name = $data[$i][2];
                $user->first_name = $data[$i][0];
                $user->last_name = $data[$i][1];
                $user->activated = 1;
                $user->admin_ip_address = $ipAddress->getClientIp();
                $user->email = $data[$i][2];
                $user->password = $random_password;
                $user->created_by = $currentUser->id;
                $user->save();
                $user->profile()->save($profile);
                $user->attachRole($roles->id);

                $mdata = [
                    'username' => $user->email,
                    'password' => $random_password,
                    'createdby' => $currentUser->name

                ];
                $data[$i][3] = "User Saved Successfully";
                $data[$i][4] = "Email  Sent Successfully";
                try {
                    $mail_status = Mail::to($user->email)->send(new CUsersMail($mdata));
                    //If error from Mail::send
                    if (!is_null($mail_status) && $mail_status->failures() > 0) {
                        //Fail for which email address...
                        foreach (Mail::failures as $address) {
                            print $address . ', ';
                        }
                        exit;
                    }

                } catch (Exception $e) {
                    // Get error here
                    print $e->getMessage();
                    exit;
                }

            }

        }


        return redirect('/')->with('success', 'CSV Uploaded Successfully Please Have A Look Below to See CSV Result', 'created_by', 'data');
    }


    /**
     * Admin Truncate Login
     */
    public function truncateData()
    {
        try {

        $user = \Illuminate\Support\Facades\Auth::user();
        if($user->id === 1){

            \Illuminate\Support\Facades\DB::select("TRUNCATE rate_cards;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE matched_advisor_list;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE discovery_call_logs;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE client_requests;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE `feedback_deal_finish`;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE client_advisor_deals;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE `rates_card_offers`;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE appointment_advisor_client;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE stripe_payment_logs;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE `appointment_advisor_availability`;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE answers;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE advisor_requests;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE role_user;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE persons;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE users;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE videos;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE quotes;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE notifications;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE blog_etc_categories;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE blog_etc_comments;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE blog_etc_posts;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE blog_etc_uploaded_photos;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE youtube_access_tokens;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE advisor_client_type;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE connect_users;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE user_type_ranges;");
            \Illuminate\Support\Facades\DB::select("TRUNCATE guidefi_commissions;");

            \Illuminate\Support\Facades\DB::select('INSERT INTO `users` (`name`, `email`, `password`, `activated`, `is_blocked`, `status`, `social_signup`, `is_questionare_completed`, `no_of_logins`) VALUES ("Admin", "admin@admin.com", "$2y$10$NCkpVQ5G01MCbeSGRn4QKO.youkIHOGzfWzqbd/s9De53NPfGWfqi", "1", "0", "active", "0", "1", "1");');
            \Illuminate\Support\Facades\DB::select(" INSERT INTO `role_user` (`role_id`, `user_id`)VALUES('1', '1');");
            return response()->json([
                'status' => false,
                'message' => "All the records are cleards and an admin record creadted",
                'data' => null,
            ], 200);

        }else{
            return response()->json([
                'status' => false,
                'message' => "Not allowed to call this url, This can only be called from local system",
                'data' => null,
            ], 200);
        }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ]);
        }
    }
}
