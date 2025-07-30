<?php

namespace App\Http\Controllers;

use App\Advisorrequest;
use App\ClientAdvisorDeals;
use App\FeedbackDealFinish;
use App\Models\AdvisorClientType;
use App\models\ClientTypes;
use App\Models\Person;
use App\Models\Profile;
use App\Models\Theme;
use App\Repositories\EmailManager;
use App\StripePaymentLog;
use App\User;
use App\Package;
use App\Notifications\SendGoodbyeEmail;
use App\Traits\CaptureIpTrait;
use App\UserTypeRanges;
use Cassandra\Type\UserType;
use File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Image;
use Validator;
use View;
use Webpatser\Uuid\Uuid;

class AdvisorRequestsController extends Controller
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
     * Display the specified resource.
     *
     * @param string $username
     *
     * @return Response
     */

   
       
    public function index()
    {

            $status = [

                [
                    'id' => 1,
                    'name' => 'pending',
                    'value' =>'pending',
                ],

                [
                    'id' => 2,
                    'name' => 'approved',
                    'value' =>'active'
                ],

                [
                     'id' => 3,
                     'name' => 'declined',
                     'value' =>'declined'
                 ],
                /* [
                     'id' => 4,
                     'name' => 'approved'
                 ]*/

            ];
        try {

            $advisor_requests = Advisorrequest::with('user.person')->where('status','!=','active')->orderBy('created_at','DESC')->get();

            return view('pages.admin.advisor-requests', compact('advisor_requests','status'));
        } catch (ModelNotFoundException $exception) {
            abort(404);
        }


    }

    public function updateAdvisorStatus(Request $request, $id)
    {

       $advisor = Advisorrequest::find($id);
       $userId = !is_null($advisor->user_id)?$advisor->user_id:null;
       if($advisor->update(['status'=>$request->status])){
           $status = ($request->status == 'active')?'active':$request->status;
            $user = User::whereId($userId)->update(['status'=>$status]);

           if($request->status == 'active'){
               /* Email & Notification Section for  */
            $html = "Your account has been activated and you are all set. Log back into the Guidefi app to start an application to become a part of the Guidefi Advisor Network. 
                    <br>The Guidefi Advisor Network is an exclusive network of the best most culturally competent financial advisors, coaches, and counselors in the United States. 
                                                  <br>For technical difficulties with account activation, please email<br><br>";
               EmailManager::sendMailQueue('ACCOUNT ACTIVATION',$html, $advisor->user->name, $advisor->user->email);
           }

         return response()->json(['code'=>1,'message' => 'Advisor status '.$request->status . ' Successfully !!','status'=>$status]);
       }

    }


    public function AdvisorList()
    {
         $advisors = User::whereHas('roles', function ($q) {
                $q->where('name', 'Advisor');
            })->where('status','active')->orderBy('updated_at','DESC')->get();

        //no of users,not doing login after account activation
        $noOfNonLogin = User::whereHas('roles', function ($q) {
            $q->where('name', 'Advisor');
        })->where('status','active')->where('login_after_register',0)->count();
        return view('pages.admin.advisors',compact('advisors','noOfNonLogin'));
    }

    public function AdvisorClientType()
    {
        $advisors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Advisor');
        })->get();


        return view('pages.admin.advisorClientType',compact('advisors'));
    }
    public function advisorListDetail($id)
    {

        $advisorDetail = User::find($id);
        $types = Config::get('constants.CLIENT_TYPE') ? Config::get('constants.CLIENT_TYPE') : [];
        $advisors = FeedbackDealFinish::where('advisor_id', $id)->with('advisor.person')->get();
        $payments = [];
        $advisorClientType = AdvisorClientType::where('user_id',$id)->first();

        $selectedAdvisorType=[];
        if(!is_null($advisorClientType)){
            $selectedAdvisorType =AdvisorClientType::where('user_id',$id)->pluck('client_type');// ($advisorClientType->clientTypes->pluck('type'));
            if(!is_null($advisorClientType->user)){
                $payments=StripePaymentLog::where('customer_email',$advisorClientType->user->email)->get();
            }
        }

        return view('pages.admin.advisors.show',compact('advisorDetail','advisors','advisorClientType','types','selectedAdvisorType','payments'));
    }
    public function detail($id)
    {
        $advisorDetail = User::find($id);
        $types = UserTypeRanges::all();
        $advisors = FeedbackDealFinish::where('advisor_id', $id)->with('advisor.person')->get();
        $payments = null;
        $advisorClientType = AdvisorClientType::where('user_id',$id)->first();
        $paidByAdvisor =null;
        $paidForAdvisor =null;
        $selectedAdvisorType=null;
        if(!is_null($advisorClientType)){
            $selectedAdvisorType = AdvisorClientType::where('user_id',$id)->pluck('client_type');
        if(!is_null($advisorClientType->user)){
            $paidByAdvisor = StripePaymentLog::where('customer_email',$advisorClientType->user->email)->get();
            $paidForAdvisor = StripePaymentLog::where('advisor_id', $id)->get();
        }
        }

        $totalAmount = StripePaymentLog::where('advisor_id', $id)->sum('amount_paid');

        $deals =  ClientAdvisorDeals::with('offer')->with('appointment')
            ->with('match')->with('advisor')->where('advisor_id', $id)->get();
        return view('pages.admin.advisorsDeatail',compact('totalAmount','paidForAdvisor','paidByAdvisor','advisorDetail','advisors','advisorClientType','types','selectedAdvisorType','payments','deals'));
    }

}
