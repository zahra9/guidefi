<?php

namespace App\Console\Commands;

use App\Api\V1\Helpers\FcmController;
use App\Payment;
use App\SubscriptionPlan;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\Stripe;
use Stripe\Subscription;

class retrieveSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve all subscribtions payments on the night.';

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
     *``
     * @return mixed
     */
    public function handle()
    {

        $payment = Payment::all();

        Stripe::setApiKey();

        $sub = Subscription::retrieve('sub_HeXW4vOcaGIoOd');

//        dd($sub);
        /*$stripe->customers->retrieve(
            'cus_HfHfK3hY5rzzUr',
            []
        );*/

//        $customer = \Stripe\Customer::all(["email" => 'asifhussain.eventcart@gmail.com']);
//        $customer2 = \Stripe\Customer::retrieve($customer['data'][0]['id'], []);



//        dd($customer2['id']);

        $allinvoices = Invoice::all(['limit' => 50, 'customer' => 'cus_HR1mzLJIkVZclL']);

        dd($allinvoices);


        dd(Carbon::parse('1596990198'));
        dd($sub);





























        $client_subscription_plans = SubscriptionPlan::where('active', 1)
            ->where('is_corporate_user', 0)
            ->whereDate('next_billing_date', Carbon::parse('2020-06-09 01:47:50.000000'))
            ->get();

        foreach ($client_subscription_plans as $key => $subscription) {


            $user = User::find($subscription->user_id);

            $customer = Customer::all(["email" => $user->email]);


            $Result = Invoice::retrieve($customer['data'][0]['subscriptions']['data'][0]['latest_invoice']);


            dd($Result['charge']);

//            dd(Carbon::parse($Result['period_start'])->format('y-m-d'));
            if(Carbon::parse($Result['period_start'])->format('y-m-d') == Carbon::parse($subscription->next_billing_date)->format('y-m-d')){
                $payment = Payment::create([
                    'user_id' => $subscription->user_id,
                    'plan_id' => $subscription->package_id,
                    'charge_id' => $Result['charge'],
                    'amount' => $Result['total']/100,
                    'stripe_subscription_id' => $Result['subscription'],
                    'stripe_id' => $token->id,
                    'card' => $data['card_no'],
                    'trial_ends_at' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s')
                ]);
            }

            if(Carbon::parse($Result['period_start'])->eq(Carbon::parse($subscription->next_billing_date))){
                dd('in if');

            }
            dd('after check');


        }
        dd($client_subscription_plans);

//        $oldSubscription = \Stripe\Subscription::all(['customer' => 'asifhussain.eventcart@gmail.com']);

//
//        $customer = \Stripe\Customer::retrieve($customer['id']);
//        dd($customer);
//        $oldSubscription = \Stripe\Subscription::all(['customer' => $customer['id']]);
//
//        dd($oldSubscription);
//        $Result = \Stripe\Invoice::all(['customer' => $customer['id']]);
//        $Result = \Stripe\Invoice::retrieve('in_1H32X4ChQR3epW4kDAn4Q7ft');
//        $Result = \Stripe\Subscription::retrieve('in_1H32X4ChQR3epW4kDAn4Q7ft');
        $Result = Subscription::retrieve('sub_HR2IEN5aTAHWbT');

        $Result = Invoice::retrieve($Result['latest_invoice']);

        if(Carbon::parse($Result['period_start'])->gt(Carbon::now())){

        }
        dd(Carbon::parse($Result['period_start'])->gt(Carbon::now()));
        dd(Carbon::parse($Result['period_start'])->format('y-m-d'));
        dd($Result['status']);


        $user->subscribedPlans()->update(['active' => 0]);
        $user->subscribedPlans()->attach($request->get('package_id'), ['active' => 1, 'billing_date' => Carbon::now()->format('Y-m-d H:i:s'), 'next_billing_date' => Carbon::now()->addMonth(1)->format('Y-m-d H:i:s')]);

        $Result = Subscription::retrieve('sub_HR2IEN5aTAHWbT');
        $Result = Subscription::retrieve($payment[3]->stripe_subscription_id);
        $fcmController = new FcmController(env('FCM_SERVER_KEY'));
        foreach ($clienthavenottherapists as $key => $user) {

            /*$payment = Payment::create([
                'user_id' => $data['user_id'],
                'plan_id' => $data['invoice'],
                'charge_id' => $charge->id,
                'amount' => $data['price'],
                'stripe_subscription_id' => 0,
                'stripe_id' => $token->id,
                'card' => $data['card_no'],
                'trial_ends_at' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s')
            ]);*/

            $description = 'Dear '. $user->name .' Please select your therapist.';
            $fcmController->pushNotification('Select Therapist', $description, 'client', $user->id);
        }
        $this->info('Therapist selection notifications are sent.');
    }
}
