<?php

namespace App\Http\Controllers;

use App\Api\V1\Helpers\StripeHelper;
use App\Appointment;
use App\Models\Profile;
use App\Package;
use App\SubscriptionPlan;
use App\User;
use App\Payment;
use App\Traits\CaptureIpTrait;
use Auth;
use App\Therapistrequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\ClientSelectedTherapist;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use jeremykenedy\LaravelRoles\Models\Role;
use Stripe\Plan;
use Stripe\Stripe;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Mail\CUsersMail;
use Illuminate\Support\Facades\Mail;

class UsersManagementController extends Controller
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
    public function index(Request $request)
    {
        if ($request->input('filter')) {
            $filter = $request->input('filter');
        } else {
            $filter = 'having-therapists';
        }

        switch ($filter) {
            case 'completed-questionair':
                $users = User::whereHas('roles', function ($q) {
                    $q->where('slug', 'client');
                })->with('payments')->with(['selectedTherapists' => function ($query) {
                    $query->where('is_active', 1)->with('therapist.user');
                }, 'subscribedPlans' => function ($query) {
                    $query->where('active', 1);
                }])->where('is_questionare_completed', 1)->get();
                break;
            case 'incomplete-questionair':
                $users = User::whereHas('roles', function ($q) {
                    $q->where('slug', 'client');
                })->with('payments')->with(['selectedTherapists' => function ($query) {
                    $query->where('is_active', 1)->with('therapist.user');
                }, 'subscribedPlans' => function ($query) {
                    $query->where('active', 1);
                }])->where('is_questionare_completed', 0)->get();
                break;
            case 'completed-registration':
                $users = User::whereHas('roles', function ($q) {
                    $q->where('slug', 'client');
                })->with('payments')->where('activated', 1)->get();
                break;
            case 'incomplete-registration':
                $users = User::whereHas('roles', function ($q) {
                    $q->where('slug', 'client');
                })->with('payments')->where('activated', 0)->get();
                break;
            case 'having-therapists':
                $clientsSelectedTherapistsIds = ClientSelectedTherapist::select('user_id')->where('is_active', 1)->get()->mapWithKeys(function ($item) {
                    return [$item['user_id'] => $item['user_id']];
                })->toArray();

                $users = User::whereHas('roles', function ($q) {
                    $q->where('slug', 'client');
                })->with('payments')->whereIn('id', $clientsSelectedTherapistsIds)->get();
                break;
            case 'not-having-therapists':
                $clientsSelectedTherapistsIds = ClientSelectedTherapist::select('user_id')->where('is_active', 1)->get()->mapWithKeys(function ($item) {
                    return [$item['user_id'] => $item['user_id']];
                })->toArray();

                $users = User::whereHas('roles', function ($q) {
                    $q->where('slug', 'client');
                })->with('payments')->whereNotIn('id', $clientsSelectedTherapistsIds)->get();
                break;
            default:
                $users = User::whereHas('roles', function ($q) {
                    $q->where('slug', 'client');
                })->with('appointments')->with('payments')->with(['selectedTherapists' => function ($query) {
                    $query->where('is_active', 1)->with('therapist.user');
                }, 'subscribedPlans' => function ($query) {
                    $query->where('active', 1);
                }])->get();
                break;
        }
        //$users = User::all();
        $roles = Role::all();

        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        $success = [];
        $stripeHelper = new StripeHelper();
        $plans = array("Basic", "Silver", "Gold");

        foreach ($users as $key => $user){
            $user->stripe_plan = 'None';
            $currPlan = SubscriptionPlan::where('user_id', $user->id)->where('active', 1)->first();
            if (isset($currPlan) && $currPlan->customer_id == '') {
                $customer = $stripeHelper->getCustomerId($user->email);
                if (isset($customer->data[0]) && $customer->data[0]->id) {
                    $customer_id = $customer->data[0]->id;
                    $currPlan->customer_id = $customer_id;
                    $currPlan->save();

                    $Invoices = $stripeHelper->getCustomerInvoices($customer_id, 999999);
                    $subscription = $stripeHelper->getSubscription($Invoices[0]['subscription']);
                    $total_spending = collect($Invoices)->sum('amount_paid');
                    if (in_array($subscription['plan']['id'], $plans)) $user->stripe_plan = $subscription['plan']['id'];
                    else if($subscription['plan']['id'] != '') {
                        if($subscription['plan']['amount']/100 == 180) $user->stripe_plan = 'Gold';
                        if($subscription['plan']['amount']/100 == 160) $user->stripe_plan = 'Silver';
                        if($subscription['plan']['amount']/100 == 140) $user->stripe_plan = 'Basic';
                    }

                    $user->total_spending = $total_spending;
                    $user->subscription_status = $subscription['status'];

                }
            }else if($currPlan){
                $Invoices = $stripeHelper->getCustomerInvoices($currPlan->customer_id, 999999);
                $subscription = $stripeHelper->getSubscription($Invoices[0]['subscription']);
                $total_spending = collect($Invoices)->sum('amount_paid');
                if (in_array($subscription['plan']['id'], $plans)) $user->stripe_plan = $subscription['plan']['id'];
                else if($subscription['plan']['id'] != '') {
                    if($subscription['plan']['amount']/100 == 180) $user->stripe_plan = 'Gold';
                    if($subscription['plan']['amount']/100 == 160) $user->stripe_plan = 'Silver';
                    if($subscription['plan']['amount']/100 == 140) $user->stripe_plan = 'Basic';
                }
                $user->total_spending = $total_spending;
                $user->subscription_status = $subscription['status'];

            }else{
                $user->stripe_plan = 'None';
                $user->total_spending = 0;
                $user->subscription_status = 'Not Found';
            }
        }

        return View('usersmanagement.show-users', compact('success', 'users', 'roles', 'newtherapists', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = Role::all();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        $data = [
            'roles' => $roles,
            'newtherapists' => $newtherapists,
        ];

        return view('usersmanagement.create-user')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|max:255|unique:users',
                'first_name' => '',
                'last_name' => '',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|same:password',
                'role' => 'required',
            ],
            [
                'name.unique' => trans('auth.userNameTaken'),
                'name.required' => trans('auth.userNameRequired'),
                'first_name.required' => trans('auth.fNameRequired'),
                'last_name.required' => trans('auth.lNameRequired'),
                'email.required' => trans('auth.emailRequired'),
                'email.email' => trans('auth.emailInvalid'),
                'password.required' => trans('auth.passwordRequired'),
                'password.min' => trans('auth.PasswordMin'),
                'password.max' => trans('auth.PasswordMax'),
                'role.required' => trans('auth.roleRequired'),
            ]
        );

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            $ipAddress = new CaptureIpTrait();
            $profile = new Profile();

            $user = User::create([
                'name' => $request->input('name'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'token' => str_random(64),
                'admin_ip_address' => $ipAddress->getClientIp(),
                'activated' => 1,
            ]);

            $user->profile()->save($profile);
            $user->attachRole($request->input('role'));
            $user->save();
            $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

            return redirect('users')->with('success', trans('usersmanagement.createSuccess'), 'newtherapists');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {

        try {

            $stripeHelper = new StripeHelper();
            $user = User::find($id);
            $roles = Role::all();
            $therapists = ClientSelectedTherapist::with('therapist', 'client')->where('user_id', $id)->get();
            $packages = $user->subscribedPlans()->where('active', 1)->first();
            $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

            // Getting payments from the stripe with status.
            $currPlan = SubscriptionPlan::where('user_id', $user->id)->where('active', 1)->first();


            if (isset($currPlan) && $currPlan->customer_id == '') {
                $customer = $stripeHelper->getCustomerId($user->email);
                if (isset($customer->data[0]) && $customer->data[0]->id) {
                    $currPlan->customer_id = $customer->data[0]->id;
                    $currPlan->save();
                }
            }

            $invoices = [];
            $NextBilling = Carbon::now()->format('m/d/Y');
            $NextPayment = 0;
            $subscriptionStatus = '';

            if (isset($currPlan) && $currPlan->customer_id != '') {

                $invoices = $stripeHelper->getCustomerInvoices($currPlan->customer_id, 100);


                $subscription = $stripeHelper->getSubscription($invoices[0]['subscription']);
                $NextBilling = Carbon::now()->format('m/d/Y');
                $NextPayment = 0;

                if ($subscription['status'] == 'canceled') {
                    $subscriptionStatus = $subscription['status'];
                } else {
                    $subscriptionStatus = $subscription['status'];
                    $UpcommingInvoice = $stripeHelper->getCustomerUpcommingInv($currPlan->customer_id);
                    $NextBilling = Carbon::parse($UpcommingInvoice['created'])->format('m/d/Y');
                    $NextPayment = $UpcommingInvoice['amount_due'] / 100;
                }

            }else{
                $customer = $stripeHelper->getCustomerId($user->email);
                if($customer->data){
                    $invoices = $stripeHelper->getCustomerInvoices($customer->data[0]->id, 100);
                    $subscription = $stripeHelper->getSubscription($invoices[0]['subscription']);
                    $NextBilling = Carbon::now()->format('m/d/Y');
                    $NextPayment = 0;

                    if ($subscription['status'] == 'canceled') {
                        $subscriptionStatus = $subscription['status'];
                    } else {
                        $subscriptionStatus = $subscription['status'];
                        $UpcommingInvoice = $stripeHelper->getCustomerUpcommingInv($currPlan->customer_id);
                        $NextBilling = Carbon::parse($UpcommingInvoice['created'])->format('m/d/Y');
                        $NextPayment = $UpcommingInvoice['amount_due'] / 100;
                    }
                }
            }
            $payments = [];
            $refunds = $stripeHelper->getAllRefunds();

            foreach($invoices as $key => $payment){
                /*$refund = $stripeHelper->getRefund($payment['charge']);

                if(count($refund['data']) > 0 && $refund['data'][0]['status'] == 'succeeded'){
                    $payment['status'] = 'Refunded';
                    $payment['amount_paid'] = $payment['amount_paid'] - ($refund['data'][0]['amount']/100);
                    $payment['reason'] = $refund['data'][0]['reason'];
                    $payment['refunded_amount'] = $refund['data'][0]['amount']/100;
                }*/


                foreach ($refunds['data'] as $rfndkey => $refund){
                    if($refund['charge'] == $payment['charge']){
                        $payment['status'] = 'Refunded';
                        $payment['amount_paid'] = $payment['amount_paid'] - ($refund['amount']/100);
                        $payment['reason'] = $refund['reason'];
                        $payment['refunded_amount'] = $refund['amount']/100;
                        break;
                    }
                }

                $payments[] = $payment;
            }
            $last_payment = Payment::where('user_id', $id)->orderBy('created_at', 'DESC')->first();
            $todayappoinments = Appointment::where('user_id', $id)->whereDate('datetime', Carbon::today())->get();
            $todayappoinments = count($todayappoinments->toArray());
            $upcomingappoinments = Appointment::where('user_id', $id)->whereDate('datetime', '>', Carbon::today())->get();
            $upcomingappoinments = count($upcomingappoinments->toArray());

            $activetherapist = [];
            $previoustherapists = [];
            $therapist_counter = 0;
            foreach ($therapists as $client) {
                if ($client['is_active'] == 0) {
                    $previoustherapists[$therapist_counter] = $client;
                    $previoustherapists[$therapist_counter]['no_of_appt'] = Appointment::where('therapist_id', $client->therapist_id)->where('is_archive', 0)->whereBetween('created_at', [$client->start_date, $client->end_date])->count();
                } else {
                    $activetherapist[$therapist_counter] = $client;

                }
                $therapist_counter++;

            }
        } catch (ModelNotFoundException $exception) {
            return back()->withError('User not found by ID ' . $id)->withInput();
        }

        return view('usersmanagement.show-user', compact('user', 'roles', 'activetherapist', 'previoustherapists', 'packages', 'payments', 'last_payment', 'todayappoinments', 'upcomingappoinments', 'newtherapists', 'NextBilling', 'NextPayment', 'subscriptionStatus'));
    }

    public function confirmuser($id)
    {
        $random_password = mt_rand(100000, 999999);
        try {
            $user = User::find($id);
            $user->activated = 1;
            $user->password = $random_password;
            $user->save();

        } catch (ModelNotFoundException $exception) {
            return back()->withError('User not found by ID ' . $id)->withInput();
        }
        $roles = Role::all();
        $users = User::whereHas('roles', function ($q) {
            $q->where('slug', 'client');
        })->with(['selectedTherapists' => function ($query) {
            $query->where('is_active', 1)->with('therapist.user');
        }, 'subscribedPlans' => function ($query) {
            $query->where('active', 1);
        }])->get();
        $mdata = [
            'username' => $user->email,
            'password' => $random_password,
            'createdby' => ''

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

        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
        $success = "User Acivated Successfully";
        return view('usersmanagement.show-users', compact('success', 'users', 'roles', 'newtherapists'));
        //return back()->with('success', 'User Acivated Successfully','users', 'roles','newtherapists');
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

        return view('usersmanagement.edit-user')->with($data);
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


    public function createPlans(Request $request)
    {
        try {
            Stripe::setApiKey();
            // This is a method to delete all the previous junk plans.
            $allPlan = Plan::all();
            foreach ($allPlan as $key => $plan) {
                $plan->delete();
            }
            // This is a method to create all the plans on stripe from db.
            $packages = Package::get();
            foreach ($packages as $key => $package) {
                Plan::create([
                    'amount' => $package->price * 100,
                    'currency' => 'usd',
                    'interval' => 'month',
                    'product' => ['name' => $package->title],
                    'id' => $package->title
                ]);
            }
            return back()->with('success', trans("All plans are deleted and new plans are created."));
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage(),
                'data' => null,
            ]);
        }
    }
}
