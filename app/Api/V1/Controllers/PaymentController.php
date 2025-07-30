<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Helpers\StripeHelper;
use App\Api\V1\Transformers\CardTransformer;
use App\Cards;
use App\Http\Controllers\Controller;

use App\Mail\SubscriptionPlanChangeMail;
use App\Package;
use App\Payment;
use App\StripePaymentLog;
use App\SubscriptionPlan;
use App\SubscriptionPlanChange;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Api\V1\Serializers\NoDataArraySerializer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class PaymentController extends Controller
{

    public function getAllCard()
    {
        try {

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $user = Auth::user();
            $stripeHelper = new StripeHelper();

            if (is_null($user->customer_id) || $user->customer_id == '') {
                $customer = $stripeHelper->addCustomer($user);
            } else {
                $customer = $stripeHelper->getCustomer($user);
            }

            if ($customer && is_string($customer)) {
                Log::error($customer);
                return response()->json([
                    'status' => true,
                    'data' => [],
                    'message' => 'No Credit Card Found, Please add a new credit card.'
                ]);
            }

            $cards = $stripeHelper->getCards($customer, []);

            if ($cards && !is_string($cards)) {
                return response()->json([
                    'status' => true,
                    'data' => $cards,
                    'message' => 'Successfully retrieved!'
                ]);
            } else {
                Log::error($cards);
                return response()->json([
                    'status' => true,
                    'data' => [],
                    'message' => 'No card found'
                ]);
            }

        } catch (Exception $ex) {
            Log::error($ex);
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage(),
                'data' => null,
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeCard(Request $request)
    {
       $this->validate($request, [
            'Y2FyZF9ubw==' => 'required', //card_no
            'ZXhwaXJ5X21vbnRo' => 'required', //expiry_month
            'ZXhwaXJ5X3llYXI=' => 'required', //expiry_year
            'Y3Zj' => 'required', //cvc
        ]);

        try {
            $user = Auth::user();
            $stripeHelper = new StripeHelper();
            if(!is_null($user->customer_id)){
                $customer = $stripeHelper->getCustomerObject($user->customer_id);
            }else{
                $customer = $stripeHelper->addCustomer($user);
            }


            if (is_string($customer)){
                return response()->json([
                    'status' => true,
                    'message' => 'No Customer created',
                    'data' => null
                ]);
            }

            $cards = [];
            if ($customer && $customer->default_source) {
                $cards = $stripeHelper->getCustomerCardsApi($customer->id, $customer->default_source, 99999);
            }

            foreach ($cards as $crdKey => $card) {
                if ($card['last4'] == substr($request->get('Y2FyZF9ubw=='), -4) && $card['expiry_year'] == $request->get('expiry_year') && $request->get('expiry_month') == $card['expiry_month']) {
                    return response()->json([
                        'status' => false,
                        'message' => 'You already have this card in your list!',
                        'data' => $cards
                    ]);
                }
            }

            //save card details



            $stripeResponse = $stripeHelper->addCard($customer->id,$request);

            if (isset($stripeResponse->id)){
                return response()->json([
                    'status' => true,
                    'message' => 'Your card added successfully.',
                    'data' => $stripeResponse
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => $stripeResponse,
                    'data' => []
                ]);
            }

        } catch (Exception $ex) {
            dd($ex);
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage(),
                'data' => null,
            ]);
        }
    }
    public function changePlan(Request $request)
    {
        $this->validate($request, [
            'plan_id' => 'required|exists:packages,id',
        ]);

        try {
            $user = Auth::user();
            $plan = Package::find($request->get('plan_id'));
            $currentPlan = SubscriptionPlan::where('user_id', $user->id)->where('active', 1)->first();

            if (is_null($currentPlan)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please activate one plan before change request',
                    'data' => null,
                ]);
            }

            if (!is_null($currentPlan) && $currentPlan->package_id == $request->get('plan_id')) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are already on the same plan.',
                    'data' => null,
                ]);
            }
            $PrevRequest = SubscriptionPlanChange::where('user_id', $user->id)->where('is_active', 1)->where('is_active', 1)->first();

            if (!is_null($PrevRequest) && $PrevRequest->new_subscription_plan_id == $request->get('plan_id')) {
                return response()->json([
                    'status' => false,
                    'message' => 'You already request for this change. Please wait for next payment date.',
                    'data' => null,
                ]);
            }

            if (!is_null($PrevRequest)) {
                $PrevRequest->is_active = 0;
                $PrevRequest->save();
            }


            $newPlan = new SubscriptionPlanChange();

            $newPlan->user_id = $user->id;
            $newPlan->current_subscription_plan_id = $currentPlan->package_id;
            $newPlan->new_subscription_plan_id = $plan->id;
            $newPlan->activation_date = $currentPlan->next_billing_date;
            $newPlan->is_active = 1;
            $newPlan->is_processed = 0;
            $newPlan->processed_date = NULL;

            $newPlan->save();

            if ($newPlan) {
                $successMessage = 'You have changed to the ' . $plan->title . ' plan. You will be charged $' . $plan->price . ' for this plan on your next billing cycle and will be able to enable the features of this plan then';

                $oldPlan = Package::find($currentPlan->package_id);

                $mdata = [
                    'client_name' => $user->name,
                    'old_plan' => $oldPlan->title,
                    'new_plan' => $plan->title,
                    'date' => Carbon::now()->format('m/d/Y'),
                    'time' => Carbon::now()->format('h:i A'),
                    'system_help_email' => env('SYSTEM_HELP_EMAIL'),
                    'message' => $successMessage,
                ];

                Mail::to($user->email)->send(new SubscriptionPlanChangeMail($mdata));
                return response()->json([
                    'status' => true,
                    'message' => $successMessage,
                    'data' => null,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong, Please contact to support!',
                    'data' => null,
                ]);
            }

        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please contact to support!',
                'data' => null,
            ]);
        }
    }


    /**
     * Setting up card as default by card ID in client_cards
     *
     * @return Response
     */
    public function setDefaultCard(Request $request)
    {
        $this->validate($request, [
            'card_id' => 'required',
        ]);

        try {
            $user = Auth::user();
            $stripeHelper = new StripeHelper();


            if (is_null($user->customer_id) || $user->customer_id == '') {
                $customer = $stripeHelper->addCustomer($user);
            } else {
                $customer = $stripeHelper->getCustomer($user);
            }

            if (!$request->has('card_id') || $request->get('card_id') == '') {
                $card = $stripeHelper->addCard($customer->id, $request);
            } else {
                $card = $stripeHelper->getCard($customer->id, $request->get('card_id'));
            }

            $customer->default_source = $card->id;
            $customer->save();

            if ($customer) {
                return response()->json([
                    'status' => true,
                    'message' => 'Your card successfully set as default!',
                    'data' => $stripeHelper->getCustomerCardsApi($customer['id'], $customer['default_source'], 99999)
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Resource not found',
                    'data' => null,
                ]);
            }

        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid card details. Please try again later with correct card details.',
                'data' => null,
            ]);
        }
    }

    public function deleteCard(Request $request)
    {
        $this->validate($request, [
            'card_id' => 'required',
            'customer_id' => 'required',
        ]);

        try {

            //  check the all cards from stripe and message user you have only one card added and you can't delete this before adding a new one.

            $user = Auth::user();

            $stripeHelper = new StripeHelper();
            $customer = $stripeHelper->getCustomerId($user->email);

            if ($customer->data && $customer->data[0]) {
                $cards = $stripeHelper->getCustomerCardsApi($customer->data[0]['id'], $customer['data'][0]['default_source'], 9999999);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'No Record found.'
                ]);
            }

            if (count($cards) > 0) {

                $deleted = $stripeHelper->deleteCustomerCardsApi($request->get('customer_id'), $request->get('card_id'));

                if ($deleted && $deleted['deleted']) {
                    return response()->json([
                        'status' => true,
                        'data' => null,
                        'message' => 'Card deleted Successfully!'
                    ]);
                } else {
                    return response()->json([
                        'status' => true,
                        'data' => null,
                        'message' => 'Something went wrong'
                    ]);
                }

            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'No card found'
                ]);
            }

        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Invalid card details. Please try again later with correct card details.',
                'data' => null,
            ]);
        }
    }

    private function checkCardExist($cardNo = null, $clientId = null)
    {
        if ($clientId) {
            $cards = Cards::where('client_id', $clientId)->select('id', 'card_number as card')->get();
        } else {
            $cards = Cards::select('id', 'card_number as card')->get();
        }
        foreach ($cards as $card) {
            if ($cardNo == $card->card) {
                return $card->id;
            }
        }
        return null;
    }


    public function paymentHistory($cardNo = null, $clientId = null)
    {

        try {
            $user = Auth::user();
            if($user->isAdvisor()){
                $stripeRecords = StripePaymentLog::where('customer_email', $user->email)->orWhere('advisor_id', $user->id)->get();
            }else{
                $stripeRecords = StripePaymentLog::where('customer_email', $user->email)->get();
            }
            $response = [];
            $clientData = null;
            foreach ($stripeRecords as $key => $value) {

                $encoded = json_decode($value->metadata);
                if($value->metadata == "{}") $encoded = '';

                $paymentForMe = false;
                $client = null;
                if($user->isAdvisor() && $value->customer_email != $user->email){
                    $paymentForMe = true;
                    $client = User::where('email', $value->customer_email)->first();
                }
                else if($user->isAdvisor() && $value->customer_email == $user->email){
//
                    $name = (isset($encoded->advisor_name) && !empty($encoded))?$encoded->advisor_name: (isset($encoded->client_name)? $encoded->client_name:'');
                    $userId = (isset($encoded->advisor_id) && !empty($encoded))?$encoded->advisor_id:(!empty($encoded) && isset($encoded->client_id)?$encoded->client_id:0);
                    $email =  (isset($encoded->advisor_email)&&  !empty($encoded))?$encoded->advisor_email:((!empty($encoded) && isset($encoded->client_email))?$encoded->client_email:'');



                    $clientData = [
                        "name" => $name,
                        "user_id" => $userId,
                        "customer_email" => $email,
                    ];
                }
                

                $response[] = [
                    "id" => $value->id,
                    "client" => $clientData,
                    "name" => !is_null($client)  ?  $client->name  : (!empty($encoded) && isset($encoded->advisor_name) ? $encoded->advisor_name : $user->name),
                    "advisor_id" => !is_null($client)  ?  $client->id  : (!empty($encoded) && isset($encoded->advisor_name)  ? $encoded->advisor_id : $user->id),
                    "customer_email" => $value->customer_email,
                    "advisor_email" => !is_null($client)  ?  $client->email  : (!empty($encoded) && isset($encoded->advisor_name)  ? $encoded->advisor_email : $user->email),
                    "contract" => !is_null($client)  ?  ''  : (!empty($encoded) && isset($encoded->advisor_name)  ? $encoded->contract_service : ''),
                    "payment_for_me" => $paymentForMe,
                    "name_on_card" => $value->customer_name,
                    "card_id" => $value->card_id,
                    "start_date" => Carbon::parse($value->period_start)->format('m/d/Y'),
                    "end_date" => Carbon::parse($value->period_end)->format('m/d/Y'),
                    "payment_date" => Carbon::parse($value->period_start)->format('m/d/Y'),
                    "card" => $value->card_last4,
                    "price" => '$' . $value->amount
                ];
            }

            if (count($response) > 0) {
                return response()->json([
                    'status' => true,
                    'data' => $response,
                    'message' => 'Successfully retrieved!'
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'No Record found'
                ]);
            }

        } catch (Exception $ex) {
            dd($ex);
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage(),
                'data' => null,
            ]);
        }
    }
}
