<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CorporateClientInvoice;
use App\CorporateClientInvoiceDetail;
use App\Models\User;
use App\Package;
use App\SubscriptionPlan;
use App\Cards;
use Illuminate\Support\Facades\Log;

class CreateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Invoices Daily ';

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
        $allusersarry=array();
        $cusers= User::whereHas('roles',function($q){
            $q->where('slug','corporate');
        })->select('id')->get();
        $j=0;
        foreach ($cusers as  $corporateuser) {

            $userlist = User::select('id')->where('created_by',  $corporateuser->id)->get();
            $totalpayable=0;
            $i=0;
            if(count($userlist)>0)
            {

                foreach ($userlist as $userid) {
                    $subscribeduserdetail = SubscriptionPlan::where('user_id', $userid->id)->where('active',  1)->where('is_corporate_user',  1)->whereNull('billing_date')->get();
                    if(count($subscribeduserdetail)>0) {
                        $packageprice = Package::where('id', $subscribeduserdetail[0]->package_id)->select('price')->first();
                        $allusersarry[$j]['users'][$i]['user_id'] = $userid->id;
                        $allusersarry[$j]['users'][$i]['user_sub_plan_id'] = $subscribeduserdetail[0]->package_id;
                        $allusersarry[$j]['users'][$i]['amount'] = $packageprice->price;
                        $allusersarry[$j]['users'][$i]['payment_status'] = 'pending';
                        $allusersarry[$j]['users'][$i]['billing_date'] = Null;
                        $allusersarry[$j]['users'][$i]['next_billing_date'] = Null;
                        $totalpayable=$totalpayable+$packageprice->price;
                        $i++;
                    }

                }
                if($totalpayable>0)
                {
                    $allusersarry[$j]['corporate_user_id']=$corporateuser->id;
                    $allusersarry[$j]['total']=$totalpayable;
                    $j++;
                }

            }

        }

        foreach ($allusersarry as $userdetail) {
            $corporate = \App\User::where('id', $userdetail['corporate_user_id'])->first();

            $invoiceid="guidefi-001000";
            $lastinvoiceid=CorporateClientInvoice::select('invoice_number')->orderby('id', 'desc')->first();
            if(!is_null($lastinvoiceid)){
                $invoicearray=explode('-',$lastinvoiceid->invoice_number);
                if(is_array($invoicearray)&& sizeof($invoicearray)>1)
                {
                    $invoiceid=("guidefi-00".($invoicearray[1]+1));
                }
            }
            $prepaidMethod = 0;
            if($corporate->corporateclient->payment_method == "Prepaid Bank Deposit"){
                $prepaidMethod = 1;
            }else{
                $carddetail=Cards::where('client_id', $userdetail['corporate_user_id'])->where('is_default' ,1)->first();
            }

            if($prepaidMethod || !is_null($carddetail))
            {
                $newinvoice=new CorporateClientInvoice();
                $newinvoice->invoice_number=$invoiceid;
                $newinvoice->corp_client_id=$userdetail['corporate_user_id'];
//                $newinvoice->corp_client_id=$corporate->corporateclient->id;
                $newinvoice->invoice_total=$userdetail['total'];
                $newinvoice->card_no= $corporate->corporateclient->payment_method == "Prepaid Bank Deposit" ? Null : $carddetail->card_number;
                $newinvoice->card_expiry=Null;
                $newinvoice->payment_status='pending';
                $newinvoice->payment_type='auto';
                $newinvoice->payment_date=date('Y-m-d H:i:s');
                $newinvoice->save();
                foreach ($userdetail['users'] as $clients) {
                    $invoicedetail=new CorporateClientInvoiceDetail();
                    $invoicedetail->invoice_id=$newinvoice->id;
                    $invoicedetail->user_id=$clients['user_id'];
                    $invoicedetail->user_sub_plan_id=$clients['user_sub_plan_id'];
                    $invoicedetail->amount=$clients['amount'];
                    $invoicedetail->payment_status=$clients['payment_status'];
                    $invoicedetail->billing_date=$clients['billing_date'];
                    $invoicedetail->next_billing_date=$clients['next_billing_date'];
                    $invoicedetail->save();
                    $updateplan=SubscriptionPlan::where('user_id',$clients['user_id'])->where('active',1)->first();
                    $updateplan->billing_date=date('Y-m-d H:i:s');
                    $updateplan->next_billing_date=Null;
                    $updateplan->save();
                }

            }


        }

        $this->info('All the invoices are Generated.');
    }
}
