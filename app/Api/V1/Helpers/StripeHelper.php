<?php


namespace App\Api\V1\Helpers;


use App\StripePaymentLog;
use App\User;
use Carbon\Carbon;
use Exception;
use http\Env\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\Plan;
use Stripe\Refund;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Subscription;
use Stripe\Token;

class StripeHelper
{
    public $stripeSecret;

    /**
     * FcmController constructor.
     * @param $serverkey : Firebase cloud server key
     */
    public function __construct()
    {
        Stripe::setApiKey(Config::get('settings.stripe_secret'));
    }

    public function refreshStripeLogTable($limit = 999999)
    {

        try {
            StripePaymentLog::truncate();

            $users = User::whereNotNull('customer_id')->pluck('customer_id');

            foreach ($users as $user) {

                $allinvoices = Invoice::all(['customer' => $user]);

                if (count($allinvoices['data']) >= 1) {
                    $stripeHelper = new StripeHelper();

                    $refunds = $stripeHelper->getAllRefunds();

                    foreach ($allinvoices as $key => $invoice) {

                        $metadata = '';
                        $advisor_id = null;
                        if ($invoice->lines && $invoice->lines['data'][0]->metadata) {
                            $advisor_id = $invoice->lines['data'][0]->metadata->advisor_id;
                            $metadata = json_encode($invoice->lines['data'][0]->metadata);
                        }

                        $charge = $stripeHelper->getCharge($invoice->charge);
                        $last4 = '';
                        $card_id = '';


                        if (!is_string($charge) && !empty($charge)) {
                            $last4 = $charge->source->last4;
                            $card_id = $charge->source->id;
                        } else if (is_string($charge) && strpos($charge, 'No such customer:')) {
                            $last4 = '';
                        }

                        $isRefunded = 0;
                        $invoice->refunded_amount = 0;
                        $invoice->refunded_status = Null;
                        $invoice->refunded_reason = Null;


                        foreach ($refunds['data'] as $rfndkey => $refund) {
                            if ($refund['charge'] == $invoice['charge']) {
                                $isRefunded = 1;
                                $invoice->status = 'Refunded';
                                $invoice->amount_paid = $invoice->amount_paid - ($refund['amount'] / 100);
                                $invoice->refunded_reason = $refund['reason'];
                                $invoice->refunded_amount = $refund['amount'] / 100;
                                $invoice->refunded_status = 'Refunded';
                                break;
                            }
                        }

                        $tmpInvoices = [
                            'customer_email' => $invoice->customer_email,
                            'advisor_id' => $advisor_id,
                            'inv_number' => $invoice->number,
                            'inv_id' => $invoice->id,
                            'card_id' => $card_id,
                            "description" => $invoice->lines->data[0]->description,
                            "amount" => $invoice->amount_paid / 100,
                            "period_end" => Carbon::parse($invoice->period_end),
                            "period_start" => Carbon::parse($invoice->period_start),
                            "created" => Carbon::parse($invoice->created),
                            "status" => $invoice->status,
                            'invoice_pdf' => $invoice->invoice_pdf,
                            'is_refunded' => $isRefunded,
                            'charge' => $invoice->charge,
                            'refunded_reason' => $invoice->refunded_reason,
                            'refunded_amount' => $invoice->refunded_amount,
                            'refunded_status' => $invoice->refunded_status,
                            'card_last4' => '****' . $last4,
                            'ended_at' => Carbon::parse($invoice->period_end)->format('m/d/Y'),
                            "object" => $invoice->invoiceobject,
                            "account_country" => $invoice->account_country,
                            "account_name" => $invoice->account_name,
                            'amount_due' => $invoice->amount_due / 100,
                            'amount_paid' => $invoice->amount_paid / 100,
                            'amount_remaining' => $invoice->amount_remaining / 100,
                            "application_fee_amount" => $invoice->application_fee_amount / 100,
                            "attempt_count" => $invoice->attempt_count,
                            "attempted" => $invoice->attempted,
                            "auto_advance" => $invoice->auto_advance,
                            "billing" => $invoice->billing,
                            "billing_reason" => $invoice->billing_reason,
                            "collection_method" => $invoice->collection_method,
                            "currency" => $invoice->currency,
                            "custom_fields" => $invoice->custom_fields,
                            "customer" => $invoice->customer,
                            "customer_address" => $invoice->customer_address,
                            "customer_name" => $invoice->customer_name,
                            "customer_phone" => $invoice->customer_phone,
                            "customer_shipping" => $invoice->customer_shipping,
                            "customer_tax_exempt" => $invoice->customer_tax_exempt,
//                            "customer_tax_ids" => $invoice->customer_tax_ids,
                            "default_payment_method" => $invoice->default_payment_method,
                            "default_source" => $invoice->default_source,
//                            "default_tax_rates" => $invoice->default_tax_rates,
                            "discount" => !is_null($invoice->discount) ? $invoice->discount->id : null,
                            "due_date" => $invoice->due_date,
                            "ending_balance" => $invoice->ending_balance,
                            "footer" => $invoice->footer,
                            "hosted_invoice_url" => $invoice->hosted_invoice_url,
                            "livemode" => $invoice->livemode,
                            "metadata" => $metadata,
                            "next_payment_attempt" => $invoice->next_payment_attempt,
                            "paid" => $invoice->paid,
                            "payment_intent" => $invoice->payment_intent,
                            "post_payment_credit_notes_amount" => $invoice->post_payment_credit_notes_amount,
                            "pre_payment_credit_notes_amount" => $invoice->pre_payment_credit_notes_amount,
                            "receipt_number" => $invoice->receipt_number,
                            "starting_balance" => $invoice->starting_balance,
                            "statement_descriptor" => $invoice->statement_descriptor,
//                        "status_transitions" => $invoice->status_transitions,
                            "subscription" => $invoice->subscription,
                            "subtotal" => $invoice->subtotal,
                            "tax" => $invoice->tax,
                            "tax_percent" => $invoice->tax_percent,
                            "total" => $invoice->total,
//                            "total_tax_amounts" => $invoice->total_tax_amounts,
                            "transfer_data" => $invoice->transfer_data,
                            "webhooks_delivered_at" => Carbon::parse($invoice->webhooks_delivered_at)->format('m/d/Y h:i A'),
                            "inv_lines" => $invoice->lines->url,
                            "created_at" => Carbon::now()
                        ];
                        $result = StripePaymentLog::insert($tmpInvoices);
//            }

                    }
                }
                else {

                    $allinvoices = Charge::all(['customer' => $user]);
                    Log::info('>><<<');
                    Log::info($allinvoices);
                    $customer = Customer::retrieve($user, []);

                    if (count($allinvoices['data']) >= 1) {
                        $stripeHelper = new StripeHelper();

                        $refunds = $stripeHelper->getAllRefunds();

                        foreach ($allinvoices as $key => $invoice) {

                            $advisor_id = null;
                            $advisor_id = 11;
                            $metadata = json_encode($invoice->metadata);

                            $last4 = json_encode($invoice->payment_method_details->card->last4);
                            $card_id = json_encode($invoice->payment_method);

                            $isRefunded = 0;
                            $invoice->refunded_amount = 0;
                            $invoice->refunded_status = Null;
                            $invoice->refunded_reason = Null;

                            foreach ($refunds['data'] as $rfndkey => $refund) {

                                if ($refund['charge'] == $invoice['charge']) {
                                    $isRefunded = 1;
                                    $invoice->status = 'Refunded';
                                    $invoice->amount_paid = $invoice->amount_paid - ($refund['amount'] / 100);
                                    $invoice->refunded_reason = $refund['reason'];
                                    $invoice->refunded_amount = $refund['amount'] / 100;
                                    $invoice->refunded_status = 'Refunded';
                                    break;
                                }

                            }
                            $tmpInvoices = [
                                'customer_email' => $customer->email,
                                'advisor_id' => $advisor_id,
                                'inv_number' => null,
                                'inv_id' => null,
                                'card_id' => $card_id,
                                "description" => $invoice->description,
                                "amount" => $invoice->amount / 100,
                                "period_end" => null,
                                "period_start" => null,
                                "created" => Carbon::parse($invoice->created),
                                "status" => $invoice->status,
                                'invoice_pdf' => null,
                                'is_refunded' => $isRefunded,
                                'charge' => $invoice->charge,
                                'refunded_reason' => $invoice->refunded_reason,
                                'refunded_amount' => $invoice->amount_refunded,
                                'refunded_status' => $invoice->refunded_status,
                                'card_last4' => '****' . $last4,
                                'ended_at' => null,
                                "object" => null,
                                "account_country" => $customer->sources->data[0]['country'],
                                "account_name" => $customer->name,
                                'amount_due' => $invoice->amount / 100,
                                'amount_paid' => $invoice->amount / 100,
                                'amount_remaining' => $invoice->amount / 100,
                                "application_fee_amount" => $invoice->application_fee_amount / 100,
                                "attempt_count" => $customer->sources->total_count,
                                "attempted" => null,
                                "auto_advance" => null,
                                "billing" => null,
                                "billing_reason" => null,
                                "collection_method" => null,
                                "currency" => $invoice->currency,
                                "custom_fields" => null,
                                "customer" => $invoice->customer,
                                "customer_address" => $customer->address,
                                "customer_name" => $customer->name,
                                "customer_phone" => $customer->phone,
                                "customer_shipping" => $invoice->shipping,
                                "customer_tax_exempt" => null,
                                "default_payment_method" => null,
                                "default_source" => null,
                                "discount" => null,
                                "due_date" => null,
                                "ending_balance" => null,
                                "footer" => null,
                                "hosted_invoice_url" => null,
                                "livemode" => $invoice->livemode,
                                "metadata" => $metadata,
                                "next_payment_attempt" => null,
                                "paid" => null,
                                "payment_intent" => null,
                                "post_payment_credit_notes_amount" => null,
                                "pre_payment_credit_notes_amount" => null,
                                "receipt_number" => $invoice->receipt_number,
                                "starting_balance" => null,
                                "statement_descriptor" => $invoice->description,
                                "subscription" => null,
                                "subtotal" => null,
                                "tax" => null,
                                "tax_percent" => null,
                                "total" => $invoice->amount_due / 100,
                                "transfer_data" => $invoice->transfer_data,
                                "webhooks_delivered_at" => null,
                                "inv_lines" => null,
                                "created_at" => Carbon::now()

                            ];
                            $result = StripePaymentLog::insert($tmpInvoices);

                        }
                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            Log::info('get cron job errors');
            Log::info($e->getMessage());
            return $e->getMessage();
        }
    }

    public function getCustomerInvoices($customer_id, $limit = 10000)
    {

        try {
            $allinvoices = Invoice::all(['limit' => $limit, 'customer' => $customer_id]);

            $stripeHelper = new StripeHelper();
            $tmpInvoice = [];
            $card = '************' . $stripeHelper->getCustomerCards($customer_id, 1)[0]['last4'];

            if ($card) {

            }
            foreach ($allinvoices as $key => $invoice) {

                $tmpInvoice[] = [
                    'id' => $invoice->id,
                    // not found
                    'plan_name' => $invoice->lines->data[0]->plan->id,
                    'card' => $card,
                    'ended_at' => Carbon::parse($invoice->period_end)->format('m/d/Y'),

                    "object" => $invoice->invoiceobject,
                    "account_country" => $invoice->account_country,
                    "account_name" => $invoice->account_name,
                    'amount_due' => $invoice->amount_due / 100,
                    'amount_paid' => $invoice->amount_paid / 100,
                    'amount_remaining' => $invoice->amount_remaining / 100,
                    "application_fee_amount" => $invoice->application_fee_amount / 100,
                    "attempt_count" => $invoice->attempt_count,
                    "attempted" => $invoice->attempted,
                    "auto_advance" => $invoice->auto_advance,
                    "billing" => $invoice->billing,
                    "billing_reason" => $invoice->billing_reason,
                    "charge" => $invoice->charge,
                    "collection_method" => $invoice->collection_method,
                    'created' => Carbon::parse($invoice->period_start)->format('m/d/Y h:i A'),
                    "currency" => $invoice->currency,
                    "custom_fields" => $invoice->custom_fields,
                    "customer" => $invoice->customer,
                    "customer_address" => $invoice->customer_address,
                    "customer_email" => $invoice->customer_email,
                    "customer_name" => $invoice->customer_name,
                    "customer_phone" => $invoice->customer_phone,
                    "customer_shipping" => $invoice->customer_shipping,
                    "customer_tax_exempt" => $invoice->customer_tax_exempt,
                    "customer_tax_ids" => $invoice->customer_tax_ids,
                    "default_payment_method" => $invoice->default_payment_method,
                    "default_source" => $invoice->default_source,
                    "default_tax_rates" => $invoice->default_tax_rates,
                    'description' => $invoice->lines->data[0]->description,
                    "discount" => $invoice->discount,
                    "due_date" => $invoice->due_date,
                    "ending_balance" => $invoice->ending_balance,
                    "footer" => $invoice->footer,
                    "hosted_invoice_url" => $invoice->hosted_invoice_url,
                    'invoice_pdf' => $invoice->invoice_pdf,
                    "livemode" => $invoice->livemode,
                    "metadata" => $invoice->metadata,
                    "next_payment_attempt" => $invoice->next_payment_attempt,
                    'number' => $invoice->number,
                    "paid" => $invoice->paid,
                    "payment_intent" => $invoice->payment_intent,
                    "period_end" => Carbon::parse($invoice->period_end)->format('m/d/Y h:i A'),
                    "period_start" => Carbon::parse($invoice->period_start)->format('m/d/Y h:i A'),
                    "post_payment_credit_notes_amount" => $invoice->post_payment_credit_notes_amount,
                    "pre_payment_credit_notes_amount" => $invoice->pre_payment_credit_notes_amount,
                    "receipt_number" => $invoice->receipt_number,
                    "starting_balance" => $invoice->starting_balance,
                    "statement_descriptor" => $invoice->statement_descriptor,
                    "status" => $invoice->status,
//                    "status_transitions" => $invoice->status_transitions,
                    "subscription" => $invoice->subscription,
                    "subtotal" => $invoice->subtotal,
                    "tax" => $invoice->tax,
                    "tax_percent" => $invoice->tax_percent,
                    "total" => $invoice->total,
                    "total_tax_amounts" => $invoice->total_tax_amounts,
                    "transfer_data" => $invoice->transfer_data,
                    "webhooks_delivered_at" => $invoice->webhooks_delivered_at,
                    "lines" => $invoice->lines->url

                ];
            }
            return $tmpInvoice;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getCustomerCards($customer_id, $limit = 999999)
    {
        try {
            $cards = Customer::allSources($customer_id, ['object' => 'card', 'limit' => $limit]);

            if ($cards) {
                $tmpCards = [];
                foreach ($cards as $key => $card) {
                    $tmpCards[] = [
                        'id' => $card->id,
                        "object" => $card->object,
                        "address_city" => $card->address_city,
                        "address_country" => $card->address_country,
                        "address_line1" => $card->address_line1,
                        "address_line1_check" => $card->address_line1_check,
                        "address_line2" => $card->address_line2,
                        "address_state" => $card->address_state,
                        "address_zip" => $card->address_zip,
                        "address_zip_check" => $card->address_zip_check,
                        "brand" => $card->Visa,
                        "country" => $card->DE,
                        "customer" => $card->cus_HR1mzLJIkVZclL,
                        "cvc_check" => $card->pass,
                        "dynamic_last4" => $card->dynamic_last4,
                        "exp_month" => $card->exp_month,
                        "exp_year" => $card->exp_year,
                        "fingerprint" => $card->fingerprint,
                        "funding" => $card->funding,
                        "last4" => $card->last4,
                        "metadata" => $card->metadata,
                        "name" => $card->name,
                        "tokenization_method" => $card->tokenization_method
                    ];
                }
                return $tmpCards;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public static function getCustomerId($email)
    {
        try {
            return Customer::all(["email" => $email]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getSubscription($subscription)
    {
        return Subscription::retrieve($subscription);
    }

    public static function getRefund($charge)
    {
        try {
            return Refund::all(['charge' => $charge]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getAllRefunds($limit = 999999)
    {
        try {
            return Refund::all(['limit' => $limit]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getCustomerUpcommingInv($customer_id)
    {
        try {
            return Invoice::upcoming(["customer" => $customer_id]);

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getCustomerCardsApi($customer_id, $default = '', $limit = 999999)
    {
        try {
            $StripeCards = Customer::allSources($customer_id, ['object' => 'card', 'limit' => $limit]);
            $tmpCards = [];
            $cards = [];
            foreach ($StripeCards['data'] as $crdKey => $card) {
                $isDefault = 0;
                if ($default && $default == $card['id']) $isDefault = 1;
                $tmpCards['id'] = $card['id'];
                $tmpCards['customer'] = $card['customer'];
                $tmpCards['card_number'] = '************' . $card['last4'];
                $tmpCards['last4'] = $card['last4'];
                $tmpCards['name'] = $card['name'];
                $tmpCards['brand'] = $card['brand'];
                $tmpCards['is_default'] = $isDefault;
                $tmpCards['default_source'] = $default;
                $tmpCards['expiry_year'] = $card['exp_year'];
                $tmpCards['expiry_month'] = $card['exp_month'];
                $tmpCards['card_expiry'] = Carbon::parse($card['exp_year'] . '-' . $card['exp_month'] . '-01')->format('m/Y');
                $tmpCards['created_at'] = Carbon::parse($card['exp_year'] . '-' . $card['exp_month'] . '-01')->format('Y-m-d');
                $cards[] = $tmpCards;
            }
            return $cards;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function deleteCustomerCardsApi($customer_id, $card)
    {
        try {
            return Customer::deleteSource($customer_id, $card, []);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /*
    * This portion of the code is handling the Customer.
    * */

    /*public static function addCustomer($user)
    {
        try {
            $stripe = new \Stripe\StripeClient(Config::get('settings.stripe_secret'));
            $customer = $stripe->customers->all(['email'=> $user->email]);
//            $customer =  Customer::all(["email" => $user->email]);
//            \Stripe\Customer::all(["email" => "YourDesiredEmail"]);
//            $customer = isset($customer['data']) && count($customer['data']) > 0 ? $customer['data'][0] : null;
           Log::info('<<<');
           Log::info($customer);
            $customer = isset($customer) && count($customer->data) > 0 ? $customer : null;

            if (is_null($customer)) {
                $metadata = ['user_id' => $user->id, 'lang' => $user->person->lang, 'dob' => $user->person->birth_date, 'image_url' => $user->person->image_url];
                $randomStr = rand(5,15);//str_random(4);
                $customer = Customer::create(
                    ['email' => $user->email,
                        'description' => 'A user is registered with the role of ' . $user->isClient ? 'client' : 'Advisor',
                        'invoice_prefix' => 'GUI-' . $user->id.$randomStr,
                        'metadata' => $metadata,
                        'phone' => $user->person->phone,
                        'name' => $user->name
                    ]
                );

            }
            $user->customer_id = $customer->id;
            $user->save();
            return $customer;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }*/

    public static function addCustomer($user,$client = null)
    {
        try {
            $customer = Customer::all(["email" => $user->email]);
            $customer = isset($customer['data']) && count($customer['data']) > 0 ? $customer['data'][0] : null;
            if (is_null($customer)) {

//                $metadata = ['contract_service'=> $prevDeal->descreption, "advisor_id" => $advisor_id, 'advisor_name'=>$advisor->name, 'advisor_email'=> $advisor->email];

                $metadata = ['client_id'=>$client,'user_id' => $user->id, 'lang' => $user->person->lang, 'dob' => $user->person->birth_date, 'image_url' => $user->person->image_url];
               Log::info('------');
               Log::info($metadata);
                $customer = Customer::create(
                    ['email' => $user->email,
                        'description' => 'A user is registered with the role of ' . $user->isClient ? 'client' : 'Advisor',
                        'invoice_prefix' => 'GUI' . $user->id.random_int(10,15),
                        'metadata' => $metadata,
                        'phone' => $user->person->phone,
                        'name' => $user->name
                    ]
                );

            }
            $user->customer_id = $customer->id;
            $user->save();
            return $customer;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public static function getCustomerObject($customer_id)
    {
        try {
            return Customer::retrieve($customer_id);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getCustomer($user)
    {
        try {

            if(!is_null($user->customer_id)){
                $customer = Customer::retrieve($user->customer_id, []);
                $customer = isset($customer) && count($customer->sources->data) > 0 ? $customer : null;

                if (is_null($customer)) {
                $customer = StripeHelper::addCustomer($user);
            }
                return $customer;

            }else{
                $customer = StripeHelper::addCustomer($user);
                return $customer;
            }

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /*
    * This portion of the code is handling the Cards.
    * */
    public static function addCard($customerId, $request)
    {
        try {
            $encrypt = new Encryption();

            $card = Token::create([
                'card' => [
                    'number' => $encrypt->decrypt($request->get('Y2FyZF9ubw==')),
                    'exp_month' => $encrypt->decrypt($request->get('ZXhwaXJ5X21vbnRo')),
                    'exp_year' => $encrypt->decrypt($request->get('ZXhwaXJ5X3llYXI=')),
                    'cvc' => $encrypt->decrypt($request->get('Y3Zj')),
                    'name' => $encrypt->decrypt($request->get('bmFtZQ==')),
                ],
            ]);

            $card = Customer::createSource($customerId, ['source' => $card]);

            if ($request->get('is_default') == 1) {
                $customer = Customer::retrieve($customerId);
                $customer->default_source = $card->id;
                $customer->save();
            }
            return $card;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getCard($customer, $card)
    {
        try {
            return Customer::retrieveSource($customer, $card, []);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getCards($customer)
    {
        try {
            $StripeCards = Customer::allSources($customer->id, ['object' => 'card', 'limit' => 999]);
            $tmpCards = [];
            $cards = [];
            foreach ($StripeCards['data'] as $crdKey => $card) {
                $isDefault = 0;
                if ($customer->default_source && $customer->default_source == $card['id']) $isDefault = 1;
                $tmpCards['id'] = $card['id'];
                $tmpCards['customer'] = $card['customer'];
                $tmpCards['card_number'] = '************' . $card['last4'];
                $tmpCards['last4'] = $card['last4'];
                $tmpCards['name'] = $card['name'];
                $tmpCards['brand'] = $card['brand'];
                $tmpCards['is_default'] = $isDefault;
                $tmpCards['default_source'] = $customer->default_source;
                $tmpCards['expiry_year'] = $card['exp_year'];
                $tmpCards['expiry_month'] = $card['exp_month'];
                $tmpCards['card_expiry'] = Carbon::parse($card['exp_year'] . '-' . $card['exp_month'] . '-01')->format('m/Y');
                $tmpCards['created_at'] = Carbon::parse($card['exp_year'] . '-' . $card['exp_month'] . '-01')->format('Y-m-d');
                $cards[] = $tmpCards;
            }
            return $cards;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /*
    * This portion of the code is handling the Plans.
    * */
    public static function createPlan($plan, $amount, $interval = 'month', $metadata = [], $currency = 'USD')
    {
        try {
            return Plan::create([
                'amount' => $amount,
                'currency' => $currency,
                'interval' => $interval,
                'metadata' => $metadata,
                'product' => ['name' => $plan],
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /*
     * This portion of the code is handling the subscriptions.
     * */
    public static function createSubscription($customer, $stripePlan, $description = '', $metadata = [])
    {
        try {
            return Subscription::create(
                [
                    "customer" => $customer->id,
                    "plan" => $stripePlan,
                    "description" => $description,
                    "metadata" => $metadata
                ]
            );
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public static function retriveSubscription($subscription)
    {
        try {
            return Subscription::retrieve($subscription, []);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public static function cancelSubscription($subscription)
    {
        try {
            return Subscription::cancel( $subscription, []);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public static function deleteSubscription($customer)
    {
        try {
            // Delete Old Subscriptions
            $oldSubscription = Subscription::all(['customer' => $customer->id]);
            $oldSubscription = isset($oldSubscription['data']) && count($oldSubscription['data']) > 0 ? $oldSubscription['data'] : null;
            // ALL old subscriptions deleted!
            if (!is_null($oldSubscription)) {
                foreach ($oldSubscription as $subs) {
                        $subs->delete();
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    /*
    * This portion of the code is handling the Charge.
    * */
    public static function createCharge($customer, $metadata = [], $request, $currency)
    {
        try {
            return Charge::create(array(
                'description' => $request->descreption,
                'customer' => $customer->id,
                'amount' => $request->amount*100,
                'currency' => $currency,
                'metadata' => $metadata
            ));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    /*
    * This portion of the code is handling the Charge.
    * */
    public static function getCharge($charge)
    {
        try {
            return Charge::retrieve($charge);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    /*
    * This portion of the code is handling the Insertion into stripe log table.
    * */
    public static function createStripeLog($user, $request, $card, $chargeId, $currency, $subscriptionId, $subscription, $metadata = [], $deal = null)
    {

        try {
            $tmpInvoices[] = [
                'customer_email' => $user->email,
                'card_id' => $card->id,
                'advisor_id' => is_null($request)? $deal->advisor_id : $request->get('user_id'),
                "description" => is_null($request)? $deal->descreption : $request->get('descreption'),
                "amount" => is_null($request)? $deal->deal_amout : $request->amount,
                "period_start" => Carbon::now(),
                "period_end" => Carbon::now()->addMonth(),
                "created" => Carbon::now(),
                "status" => 'paid',
                'charge' => $chargeId,
                'card_last4' => '****' . $card->last4,
                'ended_at' => Carbon::now()->addMonth()->format('m/d/Y'),
                "account_country" => 'USA',
                "account_name" => $card->name,
                "currency" => $currency,
                "customer" => $card->customer,
                "subscription" => $subscriptionId,
                "total" => is_null($request)? $deal->deal_amout : $request->amount,
                "metadata" => !is_null($subscription) ? json_encode($subscription->metadata) : json_encode($metadata),

            ];
            StripePaymentLog::insert($tmpInvoices);
            return true;
        } catch (Exception $e) {
            dd($e);
            return $e->getMessage();
        }
    }

}
