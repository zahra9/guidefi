<?php

namespace App\Console\Commands;

use App\Api\V1\Helpers\StripeHelper;
use App\Mail\TherapistSubscribedMailPaymentFailed;
use App\Mail\TherapistSubscribedMailPaymentSuccess;
use App\Package;
use App\Payment;
use App\SubscriptionPlan;
use App\SubscriptionPlanChange;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\Plan;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Token;

class ChangePakagePlanRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a job to update the user plan and make billing.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $from = Carbon::now();
        $to = Carbon::now()->addHour();

        DB::enableQueryLog();
        $changePlans = SubscriptionPlanChange::where('is_processed', 0)
            ->whereDate('activation_date', Carbon::now())
            ->whereBetween('activation_date', [$from, $to])
            ->whereNull('processed_date')->where('is_active', 1)->get();

//        dd(DB::getQueryLog());


        // We have to fetch the subscription payments of the users
        //  and check if any other on the package is reached to his renewal date and card get paid.



        if(is_null($changePlans)){
            Log::info('No change requests found to entertain');
            $this->info('No change requests found to entertain');
        }else{
            Stripe::setApiKey();

            foreach ($changePlans as $key => $changePlan){

                $user = User::where('id', $changePlan->user_id)->first();
                $plan = Package::find($changePlan->new_subscription_plan_id);
                $defaultCard = $user->cards()->select('cvv_number as cvv_no','card_number as card','expiry_month', 'expiry_year', 'is_default')->where('is_default',1)->first();

                if(is_null($defaultCard)){
                    Log::info('user '. $user->id. ' with email '.  $user->email. ' has no default card, so no payment would be done.');

                    if($user->created_by != 0)
                    {
                        $stripeHelper = new StripeHelper();
                        $customer = $stripeHelper->getCustomerId($user->email);
                        $user->subscribedPlans()->attach($plan->id,['customer_id' => $customer->data[0]->id, 'active' => 1, 'billing_date' => Carbon::now()->format('Y-m-d H:i:s')]);

                        $updateRequest = SubscriptionPlanChange::where('is_processed', 0)->whereDate('activation_date', Carbon::now())
                            ->whereNull('processed_date')->where('is_active', 1)->where('user_id', $user->id)->first();
                        $updateRequest->is_active = 0;
                        $updateRequest->is_processed = 1;
                        $updateRequest->processed_date = Carbon::now()->format('Y-m-d H:i:s');
                        $updateRequest->save();

                        Log::info('Payment plan changes successfully for the users '. $user->id);
                        $this->info('Payment plan changes successfully for the users '. $user->id);
                    }
                    return true;
                }else{
                    $isPaied = 0;
                    $transaction = DB::transaction(function() use ($user, $plan, $defaultCard, $changePlan){
                        $price = ($plan->price * 100);
                        $token = Token::create([
                            'card' => [
                                'number' => $defaultCard->card,
                                'exp_month' => $defaultCard->expiry_month,
                                'exp_year' => $defaultCard->expiry_year,
                                'cvc' => $defaultCard->cvv_no,
                            ],
                        ]);

                        $customer = Customer::all(["email" => $user->email]);
                        $customer = isset($customer['data']) && count($customer['data']) > 0 ? $customer['data'][0] : null;

                        /*if(is_null($customer)){
                            $customer = \Stripe\Customer::create(array(
                                'email' => $user->email,
                                'description' => $plan->title . " Plan - Amount" . $plan->price,
                            ));

                            // Creates a subscription plan. This can also be done through the Stripe dashboard.

                            $plans = \Stripe\Plan::all();
                            if (!is_null($plans)) {
                                $id = '';
                                foreach ($plans['data'] as $key => $val){
                                    if($val['amount'] == $price && $id == ''){
                                        $id =$val->id;
                                    }
                                }
                                $stripePlan = \Stripe\Plan::retrieve($id);
                            } else {
                                $stripePlan = \Stripe\Plan::create([
                                    'amount' => $price,
                                    'currency' => 'usd',
                                    'interval' => 'month',
                                    'product' => ['name' => $plan->title],
                                    'id' => $customer->id
                                ]);
                            }


                        }else {

                            $plans = \Stripe\Plan::all();
                            if (!is_null($plans)) {
                                $id = '';
                                foreach ($plans['data'] as $key => $val){
                                    if($val['amount'] == $price && $id == ''){
                                        $id =$val->id;
                                    }
                                }
                                $stripePlan = \Stripe\Plan::retrieve($id);
                            } else {
                                $stripePlan = \Stripe\Plan::create([
                                    'amount' => $price,
                                    'currency' => 'usd',
                                    'interval' => 'month',
                                    'product' => ['name' => $plan->title],
                                    'id' => $customer->id
                                ]);
                            }
                        }*/

                        if (is_null($customer)) {
                            $customer = Customer::create(array(
                                'email' => $user->email,
                                'description' => $plan->title . " Plan - Amount" . $plan->price,
                            ));
                        }

                        $stripePlan = Plan::retrieve($plan->title, []);

                        // Delete Old Subscriptions
                        $oldSubscription = Subscription::all(['customer' => $customer['id']]);
                        $oldSubscription = isset($oldSubscription['data']) && count($oldSubscription['data']) > 0 ? $oldSubscription['data'] : null;
                        // ALL old subscriptions deleted!
                        if(!is_null($oldSubscription)){
                            foreach ($oldSubscription as $subs){
                                $subs = Subscription::retrieve($subs->id);
                                $subs->delete();
                            }
                        }

                        // Subscribe the customer to the plan
                        $subscription = Subscription::create(array(
                            "customer" => $customer->id,
                            "plan" => $stripePlan
                        ));
                        if($subscription) $isPaied = 1;
                        if(!$subscription){
                            $charge = Charge::create(array(
                                'description' => $plan->title . " Plan - Amount " . $plan->price,
                                'customer' => $customer['id'],
                                'amount' => $price, // the mount will be consider as cent so we need to multiply with 100
                                'currency' => 'USD'
                            ));
                            if($charge) $isPaied = 1;
                        }else {
                            $charge = Invoice::retrieve($subscription['latest_invoice']);
                        }


                        $user->subscribedPlans()->update(['active' => 0]);

                        // Insert into the database
                        $payment = Payment::create([
                            'user_id' => $user->id,
                            'plan_id' => $plan->id,
                            'charge_id' => $charge->id,
                            'amount' => $plan->price,
                            'stripe_subscription_id' => $subscription->id,
                            'stripe_id' => $token->id,
                            'card' => $defaultCard->card,
                            'trial_ends_at' => Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')
                        ]);

                        $user->subscribedPlans()->attach($plan->id,['active' => 1, 'billing_date' => Carbon::now()->format('Y-m-d H:i:s'), 'next_billing_date' => Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')]);

                        $updateRequest = SubscriptionPlanChange::where('is_processed', 0)->whereDate('activation_date', Carbon::now())
                            ->whereNull('processed_date')->where('is_active', 1)->where('user_id', $user->id)->first();
                        $updateRequest->is_active = 0;
                        $updateRequest->is_processed = 1;
                        $updateRequest->processed_date = Carbon::now()->format('Y-m-d H:i:s');
                        $updateRequest->save();

                        if($isPaied == 1){
                            $mdata = [
                                'client_name'  => $user->name,
                                'package_name'  => $plan->title,
                                'package_amount'  => $plan->price,
                                'credit_card_last_4'  => str_pad(substr($defaultCard->card, -4), strlen($defaultCard->card), '*', STR_PAD_LEFT),
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
                                'credit_card_last_4'  => str_pad(substr($defaultCard->card, -4), strlen($defaultCard->card), '*', STR_PAD_LEFT),
                                'date'  => Carbon::now()->format('m/d/Y'),
                                'time'  => Carbon::now()->format('h:i A'),
                                'system_help_email'  => env('SYSTEM_HELP_EMAIL')
                            ];
                            Mail::to($user->email)->send(new TherapistSubscribedMailPaymentFailed($mdata));
                        }
                        Log::info('Payment plan changes successfully for the users '. $user->id);
                        $this->info('Payment plan changes successfully for the users '. $user->id);

                        return true;
                    });

                }

            }
        }
    }
}
