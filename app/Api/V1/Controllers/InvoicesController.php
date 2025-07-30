<?php

namespace App\Api\V1\Controllers;
use App\Api\V1\Helpers\StripeHelper;
use App\Api\V1\Transformers\PaymentInvoicesTransformer;
use App\Http\Controllers\Controller;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\SubscriptionPlan;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
//    public function index()
//    {
//        $user = Auth::user();
//        \Stripe\Stripe::setApiKey();
//
//        $customer = \Stripe\Customer::all(["email" => $user->email]);
//        if(count($customer['data']) > 0){
//            $customer_id = $customer['data'][0]['id'];
//            $invoices = \Stripe\Invoice::all(['customer' => $customer_id]);
//
//            if(count($invoices['data']) > 0){
//                $invoices = $invoices['data'];
//                $new_invoices = [];
////                dd(collect($invoices)->toArray());
//                foreach ($invoices as $k => $invoice){
//                    $new_invoices[$k]['id'] = $invoice['id'];
//                    $new_invoices[$k]['number'] = $invoice['number'];
//                    $new_invoices[$k]['amount_due'] = $invoice['amount_due'] / 100;
//                    $new_invoices[$k]['amount_paid'] = $invoice['amount_paid'] / 100;
//                    $new_invoices[$k]['amount_remaining'] = $invoice['amount_remaining'] / 100;
//                    $new_invoices[$k]['invoice_pdf'] = $invoice['invoice_pdf'];
//                    $new_invoices[$k]['description'] = $invoice['lines']['data'][0]['description'];
//                    $new_invoices[$k]['status'] = $invoice['status'];
//                    $new_invoices[$k]['created'] = Carbon::parse($invoice['created'])->format('d-m-Y h:i A');
//                }
//
//                return response()->json([
//                    'status' => true,
//                    'data' => $new_invoices,
//                    'message' => 'Invoices successfully retrieved!'
//                ]);
//
//            }else{
//                return response()->json([
//                    'status' => true,
//                    'data' => null,
//                    'message' => 'No invoice found!'
//                ]);
//            }
//        }else{
//            return response()->json([
//                'status' => false,
//                'data' => null,
//                'message' => 'Customer Does not exist!'
//            ]);
//        }
//    }

    public function index()
    {
        try{
            $user = Auth::user();
            /*$manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $payments = $user->payments()->orderBy('created_at','DESC')->get();
            $payments = new Collection($payments, new PaymentInvoicesTransformer());

            $manager = $manager->createData($payments);*/

            // Getting payments from the stripe with status.
            $currPlan = SubscriptionPlan::where('user_id', $user->id)->where('active', 1)->first();
            if($currPlan && $currPlan->customer_id == ''){
                $stripeHelper = new StripeHelper();
                $customer = $stripeHelper->getCustomerId($user->email);
                $currPlan->customer_id = $customer->data[0]->id;
                $currPlan->save();
            }

            $stripeHelper = new StripeHelper();
            $invoices = $stripeHelper->getCustomerInvoices($currPlan->customer_id, 100);

            return response()->json([
                'status' => true,
                'data' => $invoices,
//                'data' => $manager->toArray(),
                'message' => 'Invoices successfully retrieved!'
            ]);


        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something went wrong!'
            ]);
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
}
