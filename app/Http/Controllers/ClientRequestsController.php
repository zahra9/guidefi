<?php

namespace App\Http\Controllers;

use App\Advisorrequest;
use App\ClientAdvisorDeals;
use App\Models\Person;
use App\Clientrequest;
use App\FeedbackDealFinish;
use App\Models\Profile;
use App\Models\Theme;
use App\Repositories\EmailManager;
use App\StripePaymentLog;
use App\User;
use App\Package;
use App\Notifications\SendGoodbyeEmail;
use App\Traits\CaptureIpTrait;
use File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Image;
use Validator;
use View;
use Webpatser\Uuid\Uuid;

class ClientRequestsController extends Controller
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
        try {
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
                 /*[
                    'id' => 4,
                    'name' => 'approved'
                ]*/

            ];


            $client_requests = Clientrequest::with('user')->where('status','!=','active')->orderBy('created_at','DESC')->get();

            return view('pages.admin.client-requests', compact('client_requests','status'));
        } catch (ModelNotFoundException $exception) {
            abort(404);
        }


    }


      public function updateClientStatus(Request $request, $id)
        {
           $advisor = Clientrequest::find($id);
            $userId = !is_null($advisor->user_id)?$advisor->user_id:null;

            if($advisor->update(['status'=>$request->status])){
                $status = ($request->status == 'active')?'active':$request->status;
                $user = User::whereId($userId)->update(['status'=>$status]);

               if($request->status == 'active'){

                   /* Email & Notification Section for Client successfully request for an advisor */

                   $html =   "Congratulations! Your account has been activated and you are all set. <br>
                              If you have not already, be sure to Log back into the Guidefi app to finish your application to be matched with your ideal financial professional.<br>
                              While you are inside the Guidefi app, be sure to check out the informative videos and other wealth content! 
                              <br>For technical difficulties with account activation, please email<br><br>";

                   EmailManager::sendMailQueue('ACCOUNT ACTIVATION',
                       $html, $advisor->user->name, $advisor->user->email);

               }
             return response()->json(['code'=>1,'message' => 'Client status '.$request->status . ' Successfully !!','status'=>$status]);
           }
        }


    public function ClientList()
    {
            $clients = User::whereHas('roles', function ($q) {
                $q->where('name', 'Client');
            })->where('status','active')->orderBy('updated_at','DESC')->get();

        //no of users,not doing login after account activation
        $noOfNonLogin = User::whereHas('roles', function ($q) {
            $q->where('name', 'Client');
        })->where('status','active')->where('login_after_register',0)->count();
        return view('pages.admin.clients',compact('clients','noOfNonLogin'));
    }

    public function detail($id)
    {
        $clients = FeedbackDealFinish::where('client_id', $id)->with('client.person')->get();
        
        return view('pages.admin.clientDetail',compact('clients'));
    }

    public function ClientDetail($id)
    {
        $clientDetail = User::find($id);
        $advisors = FeedbackDealFinish::where('advisor_id', $id)->with('client.person')->get();
        $payments = [];
        if(!is_null($clientDetail)){
            $payments=StripePaymentLog::where('customer_email',$clientDetail->email)->get();
        }
        $deals =  ClientAdvisorDeals::with('offer')->with('appointment')
            ->with('match')->with('advisor')->where('client_id', $id)->get();

        return view('pages.admin.advisors.show',compact('clientDetail','advisors','payments','deals'));
    }

}
