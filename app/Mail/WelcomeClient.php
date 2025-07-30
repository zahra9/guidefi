<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class WelcomeClient extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user = null, $link)
    {
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $role = $this->user->roles->first()->name;
        return $this->subject('Welcome to Guidefi')->markdown('emails.welcomeClient')->with([
            'user' => $this->user,
            'role' => $role,
            'password' => '',
            'token' => $this->link
        ]);
    }
}
