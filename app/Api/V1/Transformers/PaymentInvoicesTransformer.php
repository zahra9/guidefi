<?php

namespace App\Api\V1\Transformers;

use App\Payment;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PaymentInvoicesTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */

    /**
     * List of resources to automatically include.
     *
     * @var array
     */

    /**
     * @property string name
     * @property string email
     */
    public function transform(Payment $payment)
    {
        return [
            'id' => $payment->id,
            'number' => 'IN-'.sprintf('%04d', $payment->id),
            'amount_due' => !is_null($payment->plan) ? $payment->plan->price: 0,
            'amount_paid' => $payment->amount,
            'amount_remaining' => 0,
            'invoice_pdf' => '',
            'plan_name' => !is_null($payment->plan) ? $payment->plan->title: '',
            'card' => str_pad(substr($payment->card, -4), strlen($payment->card), '*', STR_PAD_LEFT),
            'description' => !is_null($payment->plan) ? $payment->plan->title.' Plan Purchased!': '',
            'status' => 'Paid',
            'created' => Carbon::parse($payment->created_at)->format('m/d/Y h:i A'),
            'ended_at' => Carbon::parse($payment->trial_ends_at)->format('m/d/Y'), //MM/DD/YEAR
        ];
    }
}
