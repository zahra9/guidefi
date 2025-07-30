<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Helpers\FcmController;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\SelectedTherapistTransformer;
use App\Cards;
use App\clientFeedbackFromTherapist;
use App\ClientJournal;
use App\ClientSelectedAdvisor;
use App\ClientSelectedTherapist;
use App\Http\Controllers\Controller;
use App\Mail\TherapistSubscribedMailPaymentFailed;
use App\Mail\TherapistSubscribedMailPaymentSuccess;
use App\Payment;
use App\Therapist;
use App\User;
use App\Package;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Stripe\Card;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\Plan;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Token;

class ClientSelectedAdvisorController extends Controller
{

    public function get_selected_advisor(Request $request)
    {
        try {

            //Get inactive last advisor
            $user = Auth::user();
            //getting Already Exist?
            $selectedTherapists = $user->selectedAdvisor()->whereHas('advisor')->get();

            if (count($selectedTherapists) > 0) {
                $manager = new Manager();
                $manager->setSerializer(new NoDataArraySerializer());
                $questions = new Collection($selectedTherapists, new SelectedTherapistTransformer());

                $manager = $manager->createData($questions);
                return response()->json([
                    'status' => true,
                    'data' => $manager->toArray(),
                    'message' => ''
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No, Therapist selected yet!'
                ]);
            }

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Successfully added as selected!'
            ]);
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    public function getClients()
    {
        try {

            //Get inactive last advisor
            $user = Auth::user();

            $getClients = ClientSelectedAdvisor::where('is_active', 1)->where('advisor_id', $user->advisor->id)->with('client')->get();

            if (count($getClients) > 0) {
                $clients = [];
                foreach ($getClients as $key => $client) {
                    $clients[] = [
                        "id" => $client->id,
                        "user_id" => $client->user_id,
                        "advisor_id" => $client->advisor_id,
                        "is_active" => $client->is_active,
                        "start_date" => Carbon::parse($client->start_date)->format('m/d/Y h:i A'),
                        "end_date" => $client->end_date,
                        "created_at" => $client->created_at,
                        "updated_at" => $client->updated_at,
                        "client" => $client->client,
                    ];
                }

                return response()->json([
                    'status' => true,
                    'data' => $clients,
                    'message' => 'Client list retrieved.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'You do not have any active clients'
                ]);
            }

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    public function change_selected_advisor(Request $request)
    {
        try {

            $this->validate($request, [
                'advisor_id' => 'required|exists:advisors,id'
            ]);

            //Get inactive last advisor
            $user = Auth::user();
            //Checking Already Exist?
            $user->selectedTherapists()->update([
                'is_active' => 0
            ]);

            ClientSelectedTherapist::create([
                'user_id' => $user->id,
                'advisor_id' => $request->get('advisor_id'),
                'start_date' => Carbon::now(),
                'is_active' => 1,
            ]);


            // Sharing all feedback with advisor
            if ($request->has('shared_feedbacks') && $request->get('shared_feedbacks') == 1) {
                $getFeedback = clientFeedbackFromTherapist::where('user_id', $user->id)->get();
                if (count($getFeedback) > 0) {
                    $feedback = $getFeedback->pluck('id');
                    $selectedTherapist = $user->selectedTherapists()->where('is_active', 1)->first();

                    if (!is_null($selectedTherapist) || !is_null($selectedTherapist->advisor)) {
                        $user->shareFeedback()->attach($feedback, [
                            'advisor_id' => $selectedTherapist->advisor->id,
                            'type' => 'feedback',
                            'active' => 1
                        ]);
                    }
                }
            }
            $advisor = Therapist::where('id', $request->get('advisor_id'))->first();

            $fcmController = new FcmController(env('FCM_SERVER_KEY'));
            $description = $user->name . ' ' . 'has selected you as an advisor.';
            $fcmController->pushNotification('Congratulations!', $description, 'client', $advisor->user_id);

            // Sharing all feedback with advisor
            if ($request->has('shared_journals') && $request->get('shared_journals') == 1) {
                $getJournals = ClientJournal::where('client_id', $user->id)->get();
                if (count($getJournals) > 0) {
                    $journals = $getJournals->pluck('id');
                    $selectedTherapist = $user->selectedTherapists()->where('is_active', 1)->first();

                    if (!is_null($selectedTherapist) || !is_null($selectedTherapist->advisor)) {
                        $user->shareJournals()->attach($journals, [
                            'advisor_id' => $selectedTherapist->advisor->id,
                            'type' => 'journal',
                            'active' => 1
                        ]);
                    }
                }

                $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                $description = $user->name . ' has shared journals with you.';
                /*
                 $description = $user->name . '  has completed and shared today’s journal with you';
                if(Carbon::parse($journal->created_at)->diffInDays(Carbon::now()) > 1){
                    $description = $user->name . '  shared '.Carbon::now()->format('m/d/Y').' journal with you';
                }elseif(Carbon::parse($journal->created_at)->diffInDays(Carbon::now()) == 1){
                    $description = $user->name . '  has completed and shared yesterday’s journal with you';
                }
                */
                $fcmController->pushNotification('Journal Shared', $description, 'journal', $advisor->user_id);

            }


            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'SuccessFully Selected!'
            ]);

        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => null,
            ]);
        }
    }

    public function save_selected_advisor(Request $request)
    {
        return response()->json([
            'status' => false,
            'message' => 'We are not accepting payments right now. Try again later',
            'data' => null,
        ]);

        $this->validate($request, [
            'advisor_id' => 'required|exists:advisors,id',
            'package_id' => 'required|exists:packages,id',
            'card_no' => 'required',
            'expiry_month' => 'required',
            'expiry_year' => 'required',
            'cvc' => 'required',
        ]);

        try {

//            $user = User::find(11);
            $user = Auth::user();
            $plan = Package::find($request->get('package_id'));
            $advisor = Therapist::find($request->get('advisor_id'));

            DB::transaction(function () use ($request, $user, $plan, $advisor) {

                Stripe::setApiKey();


                if ($request->has('isCorporateUser') && $request->get('isCorporateUser') == 1) {

                    // get credit card of the corporate user and create a customer with that card source.

                    $corpUser = User::where('id', $user->created_by)->first();

                    if($corpUser->corporateclient->payment_method  != "Prepaid Bank Deposit")
                    {
                        $crpOwner = Cards::where('client_id',  $user->created_by)->where('is_default', 1)->select('id', 'expiry_month', 'expiry_year', 'cvv_number as cvv_no', 'card_number as card')->first();


                        $data['card_no'] = $crpOwner->card;
                        $data['expiry_month'] = $crpOwner->expiry_month;
                        $data['expiry_year'] = $crpOwner->expiry_year;
                        $data['cvc'] = $crpOwner->cvv_no;

                        // Creating a customer - If you want to create customer uncomment below code.
                        $token = Token::create([
                            'card' => [
                                'number' => $crpOwner->card,
                                'exp_month' => $crpOwner->expiry_month,
                                'exp_year' => $crpOwner->expiry_year,
                                'cvc' => $crpOwner->cvv_no,
                            ],
                        ]);
                        $customer = Customer::all(["email" => $user->email]);
                        $customer = isset($customer['data']) && count($customer['data']) > 0 ? $customer['data'][0] : null;

                        if (is_null($customer)) {
                            $customer = Customer::create(array(
                                'email' => $user->email,
                                'description' => $plan->title . " Plan - Amount " . $plan->price,
                            ));
                        }

                        $requestedPlan = Plan::retrieve($plan->title, []);

                        $card = Customer::createSource(
                            $customer['id'],
                            [
                                'source' => $token,
                            ]
                        );
                        $customer = Customer::retrieve($customer['id']);
                        $customer->default_source = $card->id;
                        $customer->save();

                        // Delete Old Subscriptions
                        $oldSubscription = Subscription::all(['customer' => $customer['id']]);
                        $oldSubscription = isset($oldSubscription['data']) && count($oldSubscription['data']) > 0 ? $oldSubscription['data'] : null;
                        // ALL old subscriptions deleted!
                        $renewSubsc = 0;
                        if (!is_null($oldSubscription)) {
                            foreach ($oldSubscription as $subs) {
                                $subs->delete();
                            }
                        }

                        $subscription = Subscription::create(array(
                            "customer" => $customer->id,
                            "plan" => $requestedPlan
                        ));

                        if(!$subscription){
                            return response()->json([
                                'status' => true,
                                'message' => 'Payment processing failed. ',
                                'data' => null,
                            ]);
                        }

                        $user->subscribedPlans()->update(['active' => 0]);
                        $user->subscribedPlans()->attach(
                            $request->get('package_id'),
                            [
                                'customer_id' => $customer['id'],
                                'active' => 1,
                                'billing_date' => Carbon::now(),
                                'next_billing_date' => Carbon::now()->addMonth(),
                                'is_corporate_user' => 1
                            ]
                        );
                    }else{
                        $user->subscribedPlans()->update(['active' => 0]);
                        $user->subscribedPlans()->attach(
                            $request->get('package_id'),
                            [
                                'customer_id' => null,
                                'active' => 1,
                                'billing_date' => null,
                                'next_billing_date' => null,
                                'is_corporate_user' => 1
                            ]
                        );
                    }

                    // Ending for the details on the stripe.
                    $user->selectedTherapists()->update([
                        'is_active' => 0
                    ]);

                    ClientSelectedTherapist::create([
                        'user_id' => $user->id,
                        'advisor_id' => $request->get('advisor_id'),
                        'start_date' => Carbon::now(),
                        'is_active' => 1,
                    ]);

                    $this->createMobileChatUser($user);
                    $userInput['is_chat_enabled'] = true;
                    $user->update($userInput);
                } else {
                    $price = ($plan->price * 100);

                    // Creating a customer - If you want to create customer uncomment below code.
                    $token = Token::create([
                        'card' => [
                            'number' => $request->get('card_no'),
                            'exp_month' => $request->get('expiry_month'),
                            'exp_year' => $request->get('expiry_year'),
                            'cvc' => $request->get('cvc'),
                        ],
                    ]);
                    $customer = Customer::all(["email" => $user->email]);
                    $customer = isset($customer['data']) && count($customer['data']) > 0 ? $customer['data'][0] : null;

                    if (is_null($customer)) {
                        $customer = Customer::create(array(
                            'email' => $user->email,
                            'description' => $plan->title . " Plan - Amount " . $plan->price,
                        ));
                    }

                    $requestedPlan = Plan::retrieve($plan->title, []);

                    $card = Customer::createSource(
                        $customer['id'],
                        [
                            'source' => $token,
                        ]
                    );
                    $customer = Customer::retrieve($customer['id']);
                    $customer->default_source = $card->id;
                    $customer->save();

                    // Delete Old Subscriptions
                    $oldSubscription = Subscription::all(['customer' => $customer['id']]);
                    $oldSubscription = isset($oldSubscription['data']) && count($oldSubscription['data']) > 0 ? $oldSubscription['data'] : null;
                    // ALL old subscriptions deleted!
                    $renewSubsc = 0;
                    if (!is_null($oldSubscription)) {
                        foreach ($oldSubscription as $subs) {
                            $subs->delete();
                        }
                    }

                    $subscription = Subscription::create(array(
                        "customer" => $customer->id,
                        "plan" => $requestedPlan
                    ));
                    $isPaied = 0;
                    if($subscription) $isPaied = 1;
                    if (!$subscription) {
                        $charge = Charge::create(array(
                            'description' => $plan->title . " Plan - Amount " . $plan->price,
                            'customer' => $customer->id,
                            'amount' => $price, // the mount will be consider as cent so we need to multiply with 100
                            'currency' => 'USD'
                        ));
                        if($charge) $isPaied = 1;
                    } else {
                        $charge = Invoice::retrieve($subscription['latest_invoice']);
                    }

                    // Insert into the database
                    $payment = Payment::create([
                        'user_id' => $user->id,
                        'plan_id' => $plan->id,
                        'charge_id' => is_null($subscription) ? $charge->id : $charge->charge,
                        'amount' => $plan->price,
                        'stripe_subscription_id' => $subscription->id,
                        'stripe_id' => $token->id,
                        'card' => $request->get('card_no'),
                        'trial_ends_at' => Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')
                    ]);

                    $exist_card = $user->cards()->where('card_number', $request->get('card_no'))->first();

                    $user->cards()->update([
                        'is_default' => 0
                    ]);

                    if (is_null($exist_card)) {
                        $cardAdded = Cards::create([
                            'card_number' => $request->get('card_no'),
                            'expiry_month' => $request->get('expiry_month'),
                            'expiry_year' => $request->get('expiry_year'),
                            'cvv_number' => $request->get('cvc'),
                            'is_default' => 1,
                        ]);

                        $cardAdded->client()->associate($user)->save();
                    } else {
                        $exist_card->update(['is_default' => 1]);
                    }

                    $user->subscribedPlans()->update(['active' => 0]);
                    $user->subscribedPlans()->attach($request->get('package_id'), ['customer_id' => $customer['id'], 'active' => 1, 'billing_date' => Carbon::now()->format('Y-m-d H:i:s'), 'next_billing_date' => Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')]);


                    $user->selectedTherapists()->update([
                        'is_active' => 0
                    ]);

                    ClientSelectedTherapist::create([
                        'user_id' => $user->id,
                        'advisor_id' => $request->get('advisor_id'),
                        'start_date' => Carbon::now(),
                        'is_active' => 1,
                    ]);

                    $this->createMobileChatUser($user);
                    $userInput['is_chat_enabled'] = true;
                    $user->update($userInput);
                }

                if($isPaied == 1){
                    $mdata = [
                        'client_name'  => $user->name,
                        'package_name'  => $plan->title,
                        'package_amount'  => $plan->price,
                        'credit_card_last_4'  => str_pad(substr($request->get('card_no'), -4), strlen($request->get('card_no')), '*', STR_PAD_LEFT),
                        'date'  => Carbon::now()->format('m/d/Y'),
                        'time'  => Carbon::now()->format('h:i A'),
                        'system_help_email'  => env('SYSTEM_HELP_EMAIL')
                    ];
                    Mail::to($user->email)->send(new TherapistSubscribedMailPaymentSuccess($mdata));
                }else{
                    $mdata = [
                        'client_name'  => $user->name,
                        'package_name'  => $plan->title,
                        'package_amount'  => $plan->price,
                        'credit_card_last_4'  => str_pad(substr($request->get('card_no'), -4), strlen($request->get('card_no')), '*', STR_PAD_LEFT),
                        'date'  => Carbon::now()->format('m/d/Y'),
                        'time'  => Carbon::now()->format('h:i A'),
                        'system_help_email'  => env('SYSTEM_HELP_EMAIL')
                    ];
                    Mail::to($user->email)->send(new TherapistSubscribedMailPaymentFailed($mdata));
                }

            });

            $fcmController = new FcmController(env('FCM_SERVER_KEY'));
            $description = $user->name . ' ' . 'has selected you as an advisor.';
            $fcmController->pushNotification('Congratulations!', $description, 'client', $advisor->user_id);

            return response()->json([
                'status' => true,
                'message' => 'Therapist successfully added, Thank you for payment!',
                'data' => null,
            ]);


        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage() !== '' ? $ex->getMessage() : 'There were some issue with the payment. Please try again later.',
                'data' => null,
            ]);
        }
    }


    /*
     * This is a private method to create a new user on comechat.
     */
    private function createMobileChatUser($user)
    {

        $payload = ['uid' => $user->id, 'name' => $user->name, "metadata" => "Guidefi User"];

        $data = json_encode($payload);

        $headers = array(
            'accept: application/json',
            'apikey: 09b6ef134bdc683176087cb6234357e071f74ba3',
            'appid: 10113fe928e8140',
            'content-type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api-us.cometchat.io/v2.0/users');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($ch);
        json_decode($content, true);
    }
}
