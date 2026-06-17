<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user_name;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_name, $token)
    {
        $this->user_name = $user_name;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reset Your Password on '.config('app.name'))
                    ->view('emails.forgot_password');
    }
}
