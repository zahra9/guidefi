<?php

namespace App\Console\Commands;

use App\CorporateClient;
use App\CorporateClientInvoice;
use App\CorporateClientInvoiceDetail;
use App\CorporateClientPrepaidPayment;
use App\Mail\PaymentFailAdminMail;
use App\Mail\PaymentFailCCAdminMail;
use App\Mail\PaymentFailCCMail;
use App\Mail\PaymentFailCCNoCardAdminMail;
use App\Mail\PaymentFailCCNoCardMail;
use App\Mail\PaymentFailMail;
use App\Mail\PaymentSuccessAdminCCMail;
use App\Mail\PaymentSuccessAdminMail;
use App\Mail\PaymentSuccessCCMail;
use App\Mail\PaymentSuccessMail;
use App\Models\User;
use App\Payment;
use Carbon\Carbon;
use App\Cards;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use App\SubscriptionPlan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Token;

class InvoicePaymentAtMidNight extends Command
{

    protected $stripepaymentRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payment for the invoices from prepaid and credit card and send emails on success or fail cases.';

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
        $invoices = CorporateClientInvoice::where('payment_status', 'pending')->get();
        foreach ($invoices as $key => $invoice) {
            $data = [];
//            $corporateClient =  CorporateClient::where('user_id', $invoice->corp_client_id)->first();
            $user =  \App\User::where('id', $invoice->corp_client_id)->first();
            $userlist =  \App\User::select('id')->where('created_by', $invoice->corp_client_id)->get()->toArray();
            foreach ($userlist as $userid) {
                $useridarray[] = $userid['id'];
            }
            $this->info('payment_method  >>> '. $user->corporateclient->payment_method);
            if($user->corporateclient->payment_method  == "Prepaid Bank Deposit")
            {
                $balance = CorporateClientPrepaidPayment::where('user_id', $user->id)->sum('total_amount');
                $invoiceTotal = CorporateClientInvoice::where('corp_client_id', $user->id)->where('payment_status', 'paid')->sum('invoice_total');

                $availableBalance = $balance - $invoiceTotal;

                $this->info('$availableBalance  >>> '. $availableBalance  .' ..... '. $invoice->invoice_total);

                if($availableBalance >= $invoice->invoice_total){
                    $this->info('$availableBalance  >>> '. $availableBalance  .' ..... '. $invoice->invoice_total);

                    $invoice->payment_status = 'paid';
                    $invoice->save();
                    CorporateClientInvoiceDetail::where('invoice_id', $invoice->id)->update(array('next_billing_date' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s'), 'payment_status' => 'paid'));
                    SubscriptionPlan::whereIn('user_id', $useridarray)->where('active', 1)->update(array('next_billing_date' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s')));

                    if(env('SEND_INVOICE_EMAILD')){
                        $mdata = [
                            'client_name'  => $invoice->invoiceClient->name,
                            'client_email'  => $invoice->invoiceClient->email,
                            'invoiceNo'  => $invoice->invoice_number,
                            'invoice_total'  => $invoice->invoice_total,
                            'payment_date'  => Carbon::now()->format('Y-m-d H:i:s'),
                            'availableBalance'  => $availableBalance,
                            'system_help_email'  => env('SYSTEM_HELP_EMAIL'),
                            'admin_email'  => env('ADMIN_EMAIL'),
                        ];

                        Mail::to(env('ADMIN_EMAIL'))->send(new PaymentSuccessAdminMail($mdata));

                        $mail_status = Mail::to($invoice->invoiceClient->email)->send(new PaymentSuccessMail($mdata));

                        if(!is_null($mail_status) && $mail_status->failures() > 0 ){
                            $this->info('Email Sending faild on this email : '. $invoice->invoiceClient->email);
                            Log::error('Email Sending faild on this email : '. $invoice->invoiceClient->email);
                        }else{
                            $this->info('Email sent to this email : '. $invoice->invoiceClient->email);
                            Log::info('Email sent on this email : '. $invoice->invoiceClient->email);
                        }
                    }

                }else{
                    if(env('SEND_INVOICE_EMAILD')){
                        $mdata = [
                            'client_name'  => $invoice->invoiceClient->name,
                            'client_email'  => $invoice->invoiceClient->email,
                            'invoiceNo'  => $invoice->invoice_number,
                            'invoice_total'  => $invoice->invoice_total,
                            'payment_date'  => Carbon::now()->format('Y-m-d H:i:s'),
                            'availableBalance'  => $availableBalance,
                            'contact_person_name'  => $invoice->invoiceClient->corporateclient->contact_person_name,
                            'system_help_email'  => env('SYSTEM_HELP_EMAIL'),
                            'admin_email'  => env('ADMIN_EMAIL'),
                        ];
                        Mail::to(env('ADMIN_EMAIL'))->send(new PaymentFailAdminMail($mdata));
                        $mail_status = Mail::to($invoice->invoiceClient->email)->send(new PaymentFailMail($mdata));
                        if(!is_null($mail_status) && $mail_status->failures() > 0 ){
                            $this->info('Email Sending faild on this email : '. $invoice->invoiceClient->email);
                            Log::error('Email Sending faild on this email : '. $invoice->invoiceClient->email);
                        }else{
                            $this->info('Email sent to this email : '. $invoice->invoiceClient->email);
                            Log::info('Email sent on this email : '. $invoice->invoiceClient->email);
                        }
                    }
                }

            }else{

                $card = Cards::where('client_id', $user->id)->where('is_default', 1)->select('id', 'expiry_month', 'expiry_year', 'cvv_number as cvv_no', 'card_number as card')->first();
                if(!is_null($card)){
                    $data['card_no'] = $card->card;
                    $data['expiry_month'] = $card->expiry_month;
                    $data['expiry_year'] = $card->expiry_year;
                    $data['cvc'] = $card->cvv_no;

                    $data['email'] = $user->email;
                    $data['user_id'] = $user->id;
                    $data['invoice'] = $invoice->invoice_number;
                    $data['price'] = $invoice->invoice_total;

                    $payment = $this->payInvoice($data);
                    if ($payment) {
                        $this->info('Invoice paid by credit card '. $invoice->id);

                        $invoice->payment_status = 'paid';
                        $invoice->save();
                        CorporateClientInvoiceDetail::where('invoice_id', $invoice->id)->update(array('billing_date' => Carbon::now()->format('Y-m-d H:i:s'),'next_billing_date' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s'), 'payment_status' => 'paid'));
                        SubscriptionPlan::whereIn('user_id', $useridarray)->where('active', 1)->update(array('next_billing_date' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s')));

                        if(env('SEND_INVOICE_EMAILD')){
                            $mdata = [
                                'client_name'  => $invoice->invoiceClient->name,
                                'client_email'  => $invoice->invoiceClient->email,
                                'invoiceNo'  => $invoice->invoice_number,
                                'invoice_total'  => $invoice->invoice_total,
                                'contact_person_name'  => $invoice->invoiceClient->corporateclient->contact_person_name,
                                'payment_date'  => Carbon::now()->format('Y-m-d H:i:s'),
                                'system_help_email'  => env('SYSTEM_HELP_EMAIL'),
                                'admin_email'  => env('ADMIN_EMAIL'),
                            ];

                            Mail::to(env('ADMIN_EMAIL'))->send(new PaymentSuccessAdminCCMail($mdata));
                            Mail::to($invoice->invoiceClient->email)->send(new PaymentSuccessCCMail($mdata));
                        }
                    }else{
                        if(env('SEND_INVOICE_EMAILD')){
                            $mdata = [
                                'client_name'  => $invoice->invoiceClient->name,
                                'client_email'  => $invoice->invoiceClient->email,
                                'invoiceNo'  => $invoice->invoice_number,
                                'invoice_total'  => $invoice->invoice_total,
                                'contact_person_name'  => $invoice->invoiceClient->corporateclient->contact_person_name,
                                'payment_date'  => Carbon::now()->format('Y-m-d H:i:s'),
                                'system_help_email'  => env('SYSTEM_HELP_EMAIL'),
                                'admin_email'  => env('ADMIN_EMAIL'),
                            ];

                            Mail::to(env('ADMIN_EMAIL'))->send(new PaymentFailCCAdminMail($mdata));
                            Mail::to($invoice->invoiceClient->email)->send(new PaymentFailCCMail($mdata));
                        }
                    }

                }else{

                    // No Card found Email.
                    if(env('SEND_INVOICE_EMAILD')){
                        $mdata = [
                            'client_name'  => $invoice->invoiceClient->name,
                            'client_email'  => $invoice->invoiceClient->email,
                            'invoiceNo'  => $invoice->invoice_number,
                            'invoice_total'  => $invoice->invoice_total,
                            'contact_person_name'  => $invoice->invoiceClient->corporateclient->contact_person_name,
                            'system_help_email'  => env('SYSTEM_HELP_EMAIL'),
                            'admin_email'  => env('ADMIN_EMAIL'),
                        ];

                        Mail::to(env('ADMIN_EMAIL'))->send(new PaymentFailCCNoCardAdminMail($mdata));
                        Mail::to($invoice->invoiceClient->email)->send(new PaymentFailCCNoCardMail($mdata));
                    }
                }

            }
        }

        $this->info('All the invoices are paied.');
    }

    public function payInvoice($data)
    {

        Stripe::setApiKey();
        $token = Token::create([
            'card' => [
                'number' => $data['card_no'],
                'exp_month' => $data['expiry_month'],
                'exp_year' => $data['expiry_year'],
                'cvc' => $data['cvc'],
            ]
        ]);

        $customer = Customer::all(["email" => $data['email']]);
        $customer = isset($customer['data']) && count($customer['data']) > 0 ? $customer['data'][0] : null;

        if (is_null($customer)) {
            $customer = Customer::create(array(
                'email' => $data['email'],
                'description' => "Invoice " . $data['invoice'] . " for Corporate User - Total in Amount $" . $data['price'],
            ));
        }

        $card = Customer::createSource($customer['id'], ['source' => $token]);
        $customer = Customer::retrieve($customer['id']);
        $customer->default_source = $card->id;
        $customer->save();

        $charge = Charge::create(array(
            'description' => " Invoice " . $data['invoice'] . " total in Amount" . $data['price'],
            'customer' => $customer->id,
            'amount' => $data['price'], // the mount will be consider as cent so we need to multiply with 100
            'currency' => 'USD'
        ));

        if($charge){
            // Check if stripe sends success messages then put a record into payment else return false
            // Insert into the database
            $payment = Payment::create([
                'user_id' => $data['user_id'],
                'plan_id' => $data['invoice'],
                'charge_id' => $charge->id,
                'amount' => $data['price'],
                'stripe_subscription_id' => 0,
                'stripe_id' => $token->id,
                'card' => $data['card_no'],
                'trial_ends_at' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s')
            ]);
            return 1;
        }else{
            return 0;
        }
    }
}
