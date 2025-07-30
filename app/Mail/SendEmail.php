<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject = null, $emailBody = null, $receiverName = null)
    {
        $this->subject = $subject;
        $this->emailBody = $emailBody;
        $this->receiverName = $receiverName;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(!is_null($this->subject ) && !is_null($this->emailBody ) && !is_null($this->receiverName)){
            return $this->subject($this->subject)->markdown('emails.customEmail')->with([
                'user' => $this->receiverName,
                'emailBody' => $this->emailBody,
            ]);
        }
    }
}
