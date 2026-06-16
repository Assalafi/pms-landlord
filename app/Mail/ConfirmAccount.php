<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmAccount extends Mailable
{
    use Queueable, SerializesModels;

    public $landlord;
    public $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($landlord, $id)
    {
        $this->landlord = $landlord;
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirm Your Email on '.config('app.name'))
                    ->view('emails.confirm_account');
    }
}
