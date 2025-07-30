<?php

namespace App\Http\Controllers;

use App\Api\V1\Helpers\StripeHelper;
use App\Person;
use App\StripePaymentLog;
use App\Therapistrequest;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use jeremykenedy\LaravelRoles\Models\Role;


class StripePaymentLogController extends Controller
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
        try{
            $stripeHelper = new StripeHelper();
//            $invoices = $stripeHelper->refreshStripeLogTable();

            if($request->input('filter')){
                $filter = $request->input('filter');
            }else{
                $filter = 'all';
            }
            $from = Carbon::parse('2019-01-01')->format('Y-m-d');
            $to = Carbon::parse('')->format('Y-m-d');

            if($request->input('from') != '' || $request->input('to') != '' || $request->input('customer_email') != ''){
                $filter = 'inputfilter';
            }
            switch($filter){
                case 'all':
                    $invoices = StripePaymentLog::all();
                    break;
                case 'paid':
                    $invoices = StripePaymentLog::where('status', $filter)->get();
                    break;
                case 'open':
                    $invoices = StripePaymentLog::where('status', $filter)->get();
                    break;
                case 'Refunded':
                    $invoices = StripePaymentLog::where('status', $filter)->get();
                    break;
                case 'void':
                    $invoices = StripePaymentLog::where('status', $filter)->get();
                    break;
                case 'canceled':
                    $invoices = StripePaymentLog::where('status', $filter)->get();
                    break;
                case 'inputfilter':
                    if($request->input('from') != ''){
                        $from = Carbon::parse($request->input('from'))->format('Y-m-d');
                    }
                    if($request->input('to') != ''){
                        $to = Carbon::parse($request->input('to'))->format('Y-m-d');
                    }

                    if($request->input('customer_email') != ''){
                        $invoices = StripePaymentLog::where('customer_email', 'like', '%' . $request->input('customer_email'). '%')
                            ->whereDate('period_start', '>=', $from)
                                ->whereDate('period_end', '<=', $to)
                                ->get();
                    }else{
                        $invoices = StripePaymentLog::whereDate('period_start', '>=', $from)
                            ->whereDate('period_end', '<=', $to)
                            ->get();
                    }

                    break;
                default:
                    $invoices = StripePaymentLog::get();
                    break;
            }
            $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
            session()->flashInput($request->input());

            return View('stripepayment_logs.show-invoices', compact('invoices', 'newtherapists', 'filter'));
        }catch (Exception $e){
            return view('errors.404');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function paymentLogs()
    {
        $paymentLogs = StripePaymentLog::get()
            ->each(function($userRole){
                $user  = User::where('email',$userRole->customer_email)->first();
                if (!is_null($user)){
                    $userRole->role = isset($user->roles->first()->name) ? $user->roles->first()->name : null;
                }
            });
        $allRoles = Role::where('name','!=','Admin')->get();

        return view('stripepayment_logs.index',compact('paymentLogs','allRoles'));
    }
}
