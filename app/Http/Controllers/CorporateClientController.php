<?php

namespace App\Http\Controllers;

use App\Api\V1\Helpers\StripeHelper;
use App\Cards;

use App\Exports\CodesExport;
use App\CorporateClientInvoice;
use App\Mail\CorporateMail;
use App\Mail\CUsersMail;
use App\Mail\NonCorporateMail;
use App\SubscriptionPlan;
use App\User;
use App\Models\Profile;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Responseb;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\CorporateUsersMail;
use Illuminate\Support\Facades\Log;
use League\Fractal\Resource\Collection;
use App\CorporateClient;
use App\Therapistrequest;
use Illuminate\Support\Facades\Mail;
use jeremykenedy\LaravelRoles\Models\Role;
use App\Http\Requests\CorporateClientCreate;
use App\Traits\CaptureIpTrait;
use App\ClientSelectedTherapist;
use App\Payment;
use App\Appointment;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\Encrypter;
use App\CorporateClientPrepaidPayment;
use App\Http\Requests\PaymentCreate;
use App\CorporateClientSignupCode;
use Maatwebsite\Excel\Facades\Excel;

class CorporateClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        // dd(base64_encode(2400));
        //dd(base64_decode('MjQwMA=='));
        $roles = Role::where('slug', 'corporate')->get();
        $user = Auth::user();
        $users = User::whereHas('roles', function ($q) {
            $q->where('slug', 'corporate');
        })->with(['selectedTherapists' => function ($query) {
            $query->where('is_active', 1)->with('therapist.user');
        }, 'subscribedPlans' => function ($query) {
            $query->where('active', 1);

        }])->with('corporateclient')->get();
        foreach ($users as $cuser) {
            $newrequests = User::where('created_by', $cuser->id)->count();
            $cuser->newrequests = $newrequests;
        }
        $corporateuser = CorporateClient::where('user_id', $user->id)->first();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
        return View('usersmanagement.show-corporateusers', compact('users', 'roles', 'newtherapists'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function inviteusers(Request $request)
    {

        try {
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
            $mail_status = Mail::to($user->email)->send(new CUsersMail($mdata));
            return Redirect::back()->with('success', 'Invitation sent Successful!');
        } catch (Exception $e) {
            return back()->with('failed', "Something Went wrong");
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Responseb
     */
    public function create()
    {
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
        $roles = Role::where('slug', 'corporate')->get();
        $mytime = Carbon::now();
        $expireyear = $mytime->toArray();
        $expireyears = array();
        for ($i = $expireyear['year']; $i < $expireyear['year'] + 6; $i++) {
            $expireyears[] = $i;
        }
        return view('usersmanagement.create-corporateuser', compact('roles', 'newtherapists', 'expireyears'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function generatecode(Request $request)
    {
        //dd($request->all());
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        $user = Auth::user();
        for ($i = 0; $i < 15; $i++) {
            $randomString = '';
            for ($j = 0; $j < 6; $j++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $signupcode = new CorporateClientSignupCode();
            $signupcode->corp_client_id = $request->input('corp_client_id');
            $signupcode->is_used = 0;
            $signupcode->user_id = Null;
            $signupcode->created_by = $user->id;
            $signupcode->sign_up_code = $randomString;
            $signupcode->save();
        }
        return back()->with('success', "Signup Code Added Succefully");
        //dd($randomString);
    }

    public function store(CorporateClientCreate $request)
    {
        $ipAddress = new CaptureIpTrait();
        $random_password = mt_rand(100000, 999999);
        $user = new User();
        $user->name = $request->input('company_name');
        $user->activated = 1;
        $user->admin_ip_address = $ipAddress->getClientIp();
        $user->email = $request->input('email');
        $user->password = $random_password;
        $user->created_by = 0;
        $user->save();
        $userid = base64_encode($user->id);
        $corporateclient = CorporateClient::create([
            'company_name' => $request->input('company_name'),
            'company_phone' => $request->input('company_phone'),
            'company_address' => $request->input('company_address'),
            'contact_person_name' => $request->input('contact_person_name'),
            'contact_person_address' => $request->input('contact_person_address'),
            'contact_person_phone' => $request->input('contact_person_phone'),
            'annonymity' => $request->input('annonymity'),
            'is_corp_email' => $request->input('is_corp_email'),
            'invitation_link' => $userid,
            'payment_method' => $request->input('payment_method'),
            'password' => $random_password,
            'user_id' => $user->id
        ]);
        if ($request->input('payment_method') === 'Credit Card') {

            /*$carddetail = new Cards();
            $carddetail->is_default=1;
            $carddetail->stripe_token_id=0;
            $carddetail->client_id=$user->id;
            $carddetail->card_number=$request->input('card_no');
            $carddetail->cvv_number=$request->input('cvv');
            $carddetail->expiry_month=$request->input('expire_month');
            $carddetail->expiry_year=$request->input('expire_year');
            $carddetail->save();*/
        } else {
            /* $bankpayment=new CorporateClientPrepaidPayment();
             $bankpayment->user_id=$user->id;
             $bankpayment->corp_client_id=$corporateclient->id;
             $bankpayment->payment_date=$request->input('payment_date');
             $bankpayment->total_amount=$request->input('prepaid_amount');
             $bankpayment->notes=$request->input('notes');
             $bankpayment->save();*/
        }

        $profile = new Profile();
//        $user->attachRole($request->input('role'));
        $role = Role::where('slug', 'Corporate')->first();
        if (!is_null($role)) {
            $user->attachRole($role->id);
        }
        $user->profile()->save($profile);
        $corporateclient->user()->associate($user)->save();

        $mdata = [
            'username' => $user->email,
            'name' => $request->input('contact_person_name'),
            'password' => $random_password,
            'invitationlink' => url('/newuser/' . $userid),
            'system_help_email' => env('system_help_email'),
        ];

        try {
            if ($request->input('is_corp_email')) {
                $mail_status = Mail::to($user->email)->send(new CorporateMail($mdata));
            } else {
                $mail_status = Mail::to($user->email)->send(new NonCorporateMail($mdata));
            }
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
        return redirect('corporateusers')->with('success', "Corporate User Added Succefully");
    }

    /**
     * Display the specified resource.
     *
     * @param Therapistrequest $therapistrequest
     * @return Response
     */

    public function addpayment(PaymentCreate $request)
    {
        $bankpayment = new CorporateClientPrepaidPayment();
        $bankpayment->user_id = $request->input('user_id');
        $bankpayment->corp_client_id = $request->input('corp_client_id');
        $bankpayment->payment_date = $request->input('payment_date');
        $bankpayment->total_amount = $request->input('prepaid_amount');
        $bankpayment->notes = $request->input('notes');
        $bankpayment->save();
        return back()->with('success', "Payment Added Succefully");
    }

    public function dowloadcsvcodes($id)
    {

        return Excel::download(new CodesExport($id), 'signupcodes.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function show($id)
    {

        try {
            $user = User::find($id);
            $roles = Role::all();
            $allsignupcodes = CorporateClientSignupCode::where('corp_client_id', $user->corporateclient->id)->get();
            $therapists = ClientSelectedTherapist::with('therapist', 'client')->where('user_id', $id)->get();
            $packages = $user->subscribedPlans()->where('active', 1)->first();
            $payments = Payment::where('user_id', $id)->get();
            $todayappoinments = Appointment::where('user_id', $id)->whereDate('datetime', Carbon::today())->get();
            $todayappoinments = count($todayappoinments->toArray());
            $upcomingappoinments = Appointment::where('user_id', $id)->whereDate('datetime', '>', Carbon::today())->get();
            $upcomingappoinments = count($upcomingappoinments->toArray());
            $allclients = User::where('created_by', $id)->count();
            $activetherapist = [];
            $previoustherapists = [];
            foreach ($therapists as $client) {
                if ($client['is_active'] == 0) {
                    $previoustherapists[] = $client;
                } else {
                    $activetherapist[] = $client;

                }


            }

            $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
            $allusers = User::whereHas('roles', function ($q) {
                $q->where('slug', 'client');
            })->with(['selectedTherapists' => function ($query) {
                $query->where('is_active', 1)->with('therapist.user');
            }, 'subscribedPlans' => function ($query) {
                $query->where('active', 1);
            }])->where('created_by', $id)->get();
            $data = [];
            $userid = $id;

            $corp_id = $user->corporateclient->id;
            $totallogins = $user->no_of_logins;
            $userObj = $user;
            $balance = CorporateClientPrepaidPayment::where('user_id', $user->id)->sum('total_amount');
            $invoiceTotal = CorporateClientInvoice::where('corp_client_id', $user->id)/*->where('payment_status', 'paid')*/ ->sum('invoice_total');


            if(!is_null($userObj->corporateclient) && $userObj->corporateclient->payment_method=='Prepaid Bank Deposit'){
                $allpayments = CorporateClientPrepaidPayment::where('user_id', $user->id)->get();
            }else{

                $usersList = User::where('created_by', $id)->get();
                $allpayments = [];
                $stripeHelper = new StripeHelper();
                foreach ($usersList as $key => $usr){

                    $currPlan = SubscriptionPlan::where('user_id', $usr->id)->where('active', 1)->first();

                    if($currPlan && $currPlan->customer_id == ''){
                        $customer = $stripeHelper->getCustomerId($usr->email);
                        if($customer->data){
                            $allpayments[] = $stripeHelper->getCustomerInvoices($customer->data[0]['id'], 100);
                        }
                    }else{
                        if(!is_null($currPlan)){
                            $allpayments[] = $stripeHelper->getCustomerInvoices($currPlan->customer_id, 100);
                        }

                    }
                }
            }
            return view('usersmanagement.show-corporateuser', compact('allsignupcodes', 'userObj', 'allpayments', 'corp_id', 'allclients', 'totallogins', 'userid', 'data', 'allusers', 'user', 'roles', 'activetherapist', 'previoustherapists', 'packages', 'payments', 'todayappoinments', 'upcomingappoinments', 'newtherapists', 'balance', 'invoiceTotal'));


        } catch (Exception $ex) {

            dd($ex);
            Log::error($ex);

            return Redirect::back()->with('error', 'Something went wrong');

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => null,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Therapistrequest $therapistrequest
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
     * @param Therapistrequest $therapistrequest
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Therapistrequest $therapistrequest
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
