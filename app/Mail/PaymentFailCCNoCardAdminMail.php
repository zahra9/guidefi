<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentFailCCNoCardAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    public  $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mdata)
    {
        //
        $this->data=$mdata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->data['client_name']. ' Did\'t added a credit card yet')->markdown('emails.invoice.payment-fail-cc-no-card-admin');
    }
}
