<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Cards;
use App\CorpClientInvLog;
use App\CorporateClientPrepaidPayment;
use App\CorporateClientSignupCode;
use App\Mail\CorporateMail;
use App\Mail\CorpUsersInvEmail;
use App\Mail\NonCorporateMail;
use App\Mail\NonCorpUsersInvEmail;
use App\Models\Profile;
use App\Package;
use App\User;
use App\Payment;
use App\Traits\CaptureIpTrait;
use Auth;
use App\Therapistrequest;
use Exception;
use Illuminate\Http\Request;
use App\ClientSelectedTherapist;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use jeremykenedy\LaravelRoles\Models\Role;
use Validator;
use App\Mail\CUsersMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\UserCreate;
use App\Http\Requests\UploadFile;
use App\Imports\ExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\CorporateUsersMail;
use App\CorporateClientInvoice;
use App\CorporateClientInvoiceDetail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\SubscriptionPlan;
use Illuminate\Support\Facades\Crypt;

class CorporateUsersManagementController extends Controller
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $user = auth()->user();

        $blockuser = $user->block_user;
        $roles = Role::all();
        $users = User::whereHas('roles', function ($q) {
            $q->where('slug', 'client');
        })->with(['selectedTherapists' => function ($query) {
            $query->where('is_active', 1)->with('therapist.user');
        }, 'subscribedPlans' => function ($query) {
            $query->where('active', 1);
        }])->where('created_by', $user->id)->where('activated', 1)->get();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        return View('corporate.usersmanagement.show-users', compact('users', 'roles', 'newtherapists', 'blockuser'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $user = auth()->user();
        $roles = Role::where('slug', 'client')->get();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        $data = [
            'roles' => $roles,
            'newtherapists' => $newtherapists,
            'created_by' => $user->id
        ];

        return view('corporate.usersmanagement.create-user')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */

    public function store(UserCreate $request)
    {

        $ipAddress = new CaptureIpTrait();
        $profile = new Profile();
        $random_password = mt_rand(100000, 999999);
        // dd($request->all());
        $user = new User();
        $user->name = $request->input('name');
        $user->first_name = $request->input('name');
        $user->last_name = $request->input('name');
        $user->activated = 1;
        $user->admin_ip_address = $ipAddress->getClientIp();
        $user->email = $request->input('email');
        $user->password = $random_password;
        $user->created_by = auth()->user()->id;
        $user->save();
        $user->profile()->save($profile);

        $role = Role::where('slug', 'client')->first();
        if (!is_null($role)) {
            $user->attachRole($role->id);
        }
        $currenyuser = auth()->user();
        $mdata = [
            'username' => $user->email,
            'password' => $random_password,
            'createdby' => $currenyuser->name

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
        //$user->save();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        return redirect('clients')->with('success', trans('usersmanagement.createSuccess'), 'newtherapists');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function sendemail($id)
    {
        $user = User::find($id);
        $random_password = mt_rand(100000, 999999);
        $user->password = $random_password;
        $user->save();
        $mdata = [
            'username' => $user->email,
            'name' => $user->name,
            'password' => $random_password

        ];
        try {
            /*if($request->input('is_corp_email')){
                $mail_status = Mail::to($user->email)->send(new CorporateMail($mdata));
            }else{
                $mail_status = Mail::to($user->email)->send(new NonCorporateMail($mdata));
            }*/

            $mail_status = Mail::to($user->email)->send(new CorporateUsersMail($mdata));
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
        return redirect('clients')->with('success', "Email About User Info Send Succefully");
    }

    public function show($id)
    {

        try {
            $user = User::find($id);
            $roles = Role::all();
            $therapists = ClientSelectedTherapist::with('therapist', 'client')->where('user_id', $id)->get();
            $packages = $user->subscribedPlans()->where('active', 1)->first();
            $payments = Payment::where('user_id', $id)->get();
            $todayappoinments = Appointment::where('user_id', $id)->whereDate('datetime', Carbon::today())->get();
            $todayappoinments = count($todayappoinments->toArray());
            $upcomingappoinments = Appointment::where('user_id', $id)->whereDate('datetime', '>', Carbon::today())->get();
            $upcomingappoinments = count($upcomingappoinments->toArray());
            //dd($todayappoinments);
            $activetherapist = [];
            $previoustherapists = [];
            foreach ($therapists as $client) {
                if ($client['is_active'] == 0) {
                    $previoustherapists[] = $client;
                } else {
                    $activetherapist[] = $client;

                }


            }
            //dd($activetherapist);
        } catch (ModelNotFoundException $exception) {
            return back()->withError('User not found by ID ' . $id)->withInput();
        }
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        return view('corporate.usersmanagement.show-user', compact('user', 'roles', 'activetherapist', 'previoustherapists', 'packages', 'payments', 'todayappoinments', 'upcomingappoinments', 'newtherapists'));
    }

    public function blockit(Request $request)
    {
        $input = $request->all();

        $user = User::find($request->input('id'));
        $user->block_user = $request->input('status');
        $user->save();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        //$data=array(['block_user'=>1]);
        //DB::table('users')->where('id', 96)->update($data);

        return response()->json(array('success' => "user blocked"), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        foreach ($user->roles as $user_role) {
            $currentRole = $user_role;
        }

        $data = [
            'user' => $user,
            'roles' => $roles,
            'newtherapists' => $newtherapists,
            'currentRole' => isset($currentRole) ? $currentRole : [],
        ];

        return view('corporate.usersmanagement.edit-user')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        $user = User::find($id);
        $emailCheck = ($request->input('email') != '') && ($request->input('email') != $user->email);
        $ipAddress = new CaptureIpTrait();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        if ($emailCheck) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'email|max:255|unique:users',
                'password' => 'present|confirmed|min:6',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'password' => 'nullable|confirmed|min:6',
            ]);
        }
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            $user->name = $request->input('name');

            if ($emailCheck) {
                $user->email = $request->input('email');
            }

            if ($request->input('password') != null) {
                $user->password = $request->input('password');
            }

            $user->detachAllRoles();
            $user->attachRole($request->input('role'));
            //$user->activated = 1;

            $user->updated_ip_address = $ipAddress->getClientIp();

            $user->save();

            return back()->with('success', trans('usersmanagement.updateSuccess'), 'newtherapists');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */

    public function savecsvfile(UploadFile $request)
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

        return View('corporate.usersmanagement.import-users', compact('newtherapists', 'created_by', 'data'));
    }

    public function savecsvold(UploadFile $request)
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
        $data[0][3] = "User Status";
        $data[0][4] = "Email Status";
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
                $user->name = $data[$i][0];
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

        return response()->json((array('data' => $data)), 200);
    }

    public function savecsv(UploadFile $request)
    {

        try {
            $currentUser = Auth::user();
            $isAdmin = 0;
            if ($currentUser->hasRole('admin')) {
                $isAdmin = 1;
                $currentUser = User::find($request->get('corporate'));
            }
            $extension = $request->file('csvfile')->extension();
            if ($extension === 'txt' || $extension === 'csv') {
                $path = $request->file('csvfile')->getRealPath();
                $data = array_map('str_getcsv', file($path));
            } else {
                $usersarray = Excel::toArray(new ExcelImport, request()->file('csvfile'));
                $data = $usersarray[0];
            }

            $data[0][3] = "User Status";
            $data[0][4] = "Email Status";
            for ($i = 1; $i < sizeof($data); $i++) {
                Log::info(sizeof($data));
                $userexist = User::where('email', $data[$i][2])->get();
                if (count($userexist) > 0) {
                    $data[$i][3] = "User Already Exist";
                    $data[$i][4] = "Email Already Sent";
                } else {
                    if (filter_var($data[$i][2], FILTER_VALIDATE_EMAIL)) {
                        $CorpClientInvLog = new CorpClientInvLog();
                        $CorpClientInvLog->corp_client_id = $currentUser->id;
                        $CorpClientInvLog->first_name = $data[$i][0];
                        $CorpClientInvLog->last_name = $data[$i][1];
                        $CorpClientInvLog->email = $data[$i][2];
                        $CorpClientInvLog->invited_by_admin = $isAdmin;
                        $CorpClientInvLog->save();
                        Log::info("Email added and invitation email ready to send.");
                        $userid = base64_encode($currentUser->id);

                        $randomString = '';
                        $is_corp_email = false;
                        if (!$currentUser->corporateclient->is_corp_email) {
                            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            $charactersLength = strlen($characters);

                            for ($j = 0; $j < 6; $j++) {
                                $randomString .= $characters[rand(0, $charactersLength - 1)];
                            }
                            $signupcode = new CorporateClientSignupCode();
                            $signupcode->corp_client_id = $currentUser->id;
                            $signupcode->is_used = 0;
                            $signupcode->user_id = Null;
                            $signupcode->email = $data[$i][2];
                            $signupcode->created_by = Auth::user()->id;
                            $signupcode->sign_up_code = $randomString;
                            $signupcode->save();

                            $is_corp_email = true;
                        }
                        $mdata = [
                            'client' => $data[$i][0] . ' ' . $data[$i][1],
                            'invitation_link' => url('/newuser/'.$userid),
                            'is_corp_email' => $is_corp_email,
                            'company_name' => $currentUser->corporateclient->company_name,
                            'system_help_email' => env('system_help_email'),
                            'signup_code' => $randomString,
                        ];

                        if($currentUser->corporateclient->is_corp_email){
                            Mail::to($data[$i][2])->send(new CorpUsersInvEmail($mdata));
                        }else{
                            Mail::to($data[$i][2])->send(new NonCorpUsersInvEmail($mdata));
//                            Mail::to($data[$i][2])->send(new CorpInvEmail($mdata));
                        }

                    } else {
                        Log::info(" is not a valid email address");
                    }
                    $data[$i][3] = "User Saved Successfully";
                    $data[$i][4] = "Email  Sent Successfully";
                }
            }
            return response()->json((array('message' => 'Invitation sent successfully.')), 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function adminsavecsv(UploadFile $request)
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
        $data[0][3] = "User Status";
        $data[0][4] = "Email Status";
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
                $user->name = $data[$i][0];
                $user->first_name = $data[$i][0];
                $user->last_name = $data[$i][1];
                $user->activated = 1;
                $user->admin_ip_address = $ipAddress->getClientIp();
                $user->email = $data[$i][2];
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

        return response()->json((array('data' => $data)), 200);
    }

    public function importcsv()
    {
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
        $created_by = auth()->user()->id;
        $data = [];

        return View('corporate.usersmanagement.import-users', compact('newtherapists', 'created_by', 'data'));
    }

    public function destroy($id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);
        $ipAddress = new CaptureIpTrait();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        if ($user->id != $currentUser->id) {
            $user->deleted_ip_address = $ipAddress->getClientIp();
            $user->save();
            $user->delete();

            return redirect('users')->with('success', trans('usersmanagement.deleteSuccess'), 'newtherapists');
        }

        return back()->with('error', trans('usersmanagement.deleteSelfError'));
    }

    public function userpayment()
    {
        $user = auth()->user();
        $blockuser = $user->block_user;
        $roles = Role::all();
        /*$users= User::where('created_by',$user->id)->whereHas('roles',function($q){
            $q->where('slug','client');
        })->has('subscribedPlans')->doesnthave('payments')->get();*/
        $users = User::whereHas('subscribedPlans', function ($q) {
            $q->where('active', 1);
            $q->where('is_corporate_user', 1);
            $q->whereNull('billing_date');
            $q->whereNull('next_billing_date');
        })->where('created_by', $user->id)->get();

        $allinvoices = CorporateClientInvoice::where('corp_client_id', $user->id)->with('invoicedetailes')->get();

        $details = $this->getinvoicePakages($allinvoices);
        $balance = CorporateClientPrepaidPayment::where('user_id', $user->id)->sum('total_amount');
        $invoiceTotal = CorporateClientInvoice::where('corp_client_id', $user->id)->where('payment_status', 'paid')->sum('invoice_total');

        return View('corporate.usersmanagement.show-payableusers', compact('user', 'users', 'roles', 'blockuser', 'allinvoices', 'details', 'balance', 'invoiceTotal'));
    }

    public function makebilling()
    {
        $user = auth()->user();
        $totalcards = Cards::where('client_id', $user->id)->where('is_default', 1)->count();
        if ($totalcards < 1 && $user->corporateclient->payment_method == "Credit Card") {
            return back()->withError('You have Not Add Any Credit Card or Make Card Default To make Billing  ');
        }

        $blockuser = $user->block_user;
        $roles = Role::all();
        $balance = CorporateClientPrepaidPayment::where('user_id', $user->id)->sum('total_amount');
        /*$users= User::where('created_by',$user->id)->whereHas('roles',function($q){
            $q->where('slug','client');
        })->has('subscribedPlans')->doesnthave('payments')->get();*/
        $users = User::whereHas('subscribedPlans', function ($q) {
            $q->where('active', 1);
            $q->where('is_corporate_user', 1);
            $q->whereNull('billing_date');
            $q->whereNull('next_billing_date');
        })->where('created_by', $user->id)->get();
        if (!count($users) > 0) {
            $allinvoices = CorporateClientInvoice::where('corp_client_id', $user->id)->with('invoicedetailes')->get();
            $details = $this->getinvoicePakages($allinvoices);
            return View('corporate.usersmanagement.show-payableusers', compact('user', 'users', 'roles', 'blockuser', 'allinvoices', 'details', 'balance'));
        } else {

            $lastinvoiceid = CorporateClientInvoice::select('invoice_number')->orderby('id', 'desc')->first();
            if (!is_null($lastinvoiceid)) {
                $invoicearray = explode('-', $lastinvoiceid->invoice_number);
                if (is_array($invoicearray) && sizeof($invoicearray) > 1) {
                    //dd($invoicearray);
                    $invoiceid = ("ayana-00" . ($invoicearray[1] + 1));
                } else {
                    $invoiceid = "ayana-001000";
                }

            } else {
                $invoiceid = "ayana-001000";
            }
            //dd($invoiceid);
            $totalpayable = 0;
            $invoiceusers = array();
            $randomtxt = Str::random(32);
            $i = 0;
            foreach ($users as $client) {
                $totalpayable = $totalpayable + $client->subscribedPlans[0]->price;
                $invoiceusers[$i]['user_id'] = $client->id;
                $invoiceusers[$i]['user_sub_plan_id'] = $client->subscribedPlans[0]->id;
                $invoiceusers[$i]['amount'] = $client->subscribedPlans[0]->price;
                $invoiceusers[$i]['payment_status'] = 'pending';
                $invoiceusers[$i]['billing_date'] = Null;
                $invoiceusers[$i]['next_billing_date'] = Null;
                $updateplan = SubscriptionPlan::where('user_id', $client->id)->where('active', 1)->first();
                $updateplan->billing_date = date('Y-m-d H:i:s');
                $updateplan->next_billing_date = Null;
                $updateplan->save();
                $i++;
            }
            //dd($invoiceusers);
            $carddetail = Cards::where('client_id', $user->id)->where('is_default', 1)->first();
            $newinvoice = new CorporateClientInvoice();
            $newinvoice->invoice_number = $invoiceid;
            $newinvoice->corp_client_id = $user->id;
            $newinvoice->invoice_total = $totalpayable;
            if ($user->corporateclient->payment_method !== "Prepaid Bank Deposit") {
                $newinvoice->card_no = $carddetail->card_number;
                $newinvoice->card_expiry = Null;
            }
//            $newinvoice->card_no= '4242424242424242';

            $newinvoice->payment_status = 'pending';
            $newinvoice->payment_type = 'manual';
            $newinvoice->payment_date = date('Y-m-d H:i:s');
            $newinvoice->save();

            for ($i = 0; $i < sizeof($invoiceusers); $i++) {
                $invoicedetail = new CorporateClientInvoiceDetail();
                $invoicedetail->invoice_id = $newinvoice->id;
                $invoicedetail->user_id = $invoiceusers[$i]['user_id'];
                $invoicedetail->user_sub_plan_id = $invoiceusers[$i]['user_sub_plan_id'];
                $invoicedetail->amount = $invoiceusers[$i]['amount'];
                $invoicedetail->payment_status = $invoiceusers[$i]['payment_status'];
                $invoicedetail->billing_date = $invoiceusers[$i]['billing_date'];
                $invoicedetail->next_billing_date = $invoiceusers[$i]['next_billing_date'];
                $invoicedetail->save();
            }
        }

        $allinvoices = CorporateClientInvoice::where('corp_client_id', $user->id)->with('invoicedetailes')->get();
        $details = $this->getinvoicePakages($allinvoices);

        return View('corporate.usersmanagement.show-payableusers', compact('user', 'users', 'roles', 'blockuser', 'allinvoices', 'details', 'balance'));
    }

    private function getinvoicePakages($allinvoices)
    {

        $pakages = Package::all();
        $details = [];

        foreach ($allinvoices as $invoiceKey => $invoice) {
            foreach ($pakages as $key => $plan) {
                $count = $allinvoices[$invoiceKey]->invoicedetailes->filter(function ($value, $key) use ($plan) {
                    return $value->user_sub_plan_id == $plan->id;
                });
                $details[$invoice->id][] = [
                    'title' => $plan->title,
                    'price' => $plan->price,
                    'package_type' => $plan->package_type,
                    'total' => count($count)
                ];
            }
        }
        return $details;
    }
}
