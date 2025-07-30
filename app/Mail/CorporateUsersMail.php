<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CorporateUsersMail extends Mailable
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
//        return $this->subject('Welcome to Ayana Therapy')->markdown('emails.usersmanagement.corporatenewrequest');
        return $this->subject('Welcome to corporate email account welcome email')->markdown('emails.usersmanagement.corporatenewrequest');
    }
}
