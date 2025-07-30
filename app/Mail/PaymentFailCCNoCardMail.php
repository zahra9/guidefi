<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentFailCCNoCardMail extends Mailable
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
        return $this->subject('No/default Credit Card is not found.')->markdown('emails.invoice.payment-fail-cc-no-card');
    }
}
