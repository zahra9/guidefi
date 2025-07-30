<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $subject;
    protected $emailBody;
    protected $senderEmail;
    protected $receiverName;
    protected $receiverEmail;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject = null, $emailBody = null, $receiverName = null, $receiverEmail = null)
    {
        $this->subject = $subject;
        $this->emailBody = $emailBody;
        $this->receiverName = $receiverName;
        $this->receiverEmail = $receiverEmail;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!is_null($this->emailBody) && !is_null($this->receiverName) && !is_null($this->receiverEmail) && !is_null($this->subject)){
//            $email = new SendEmail( $this->subject, $this->emailBody, $this->receiverName);
            Mail::to($this->receiverEmail )->send(new SendEmail( $this->subject, $this->emailBody, $this->receiverName));

    }
    }
}
