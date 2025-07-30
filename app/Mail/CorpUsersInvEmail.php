<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CorpUsersInvEmail extends Mailable
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
        return $this->subject('Guidefi Therapy User Info')->markdown('emails.crp_invi_emails.crp_users_inv_email');
    }
}
