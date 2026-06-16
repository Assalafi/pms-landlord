<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;
    public $landlord;
    public $unit;
    public $property;
    public $address;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tenant, $landlord, $unit, $property, $address, $password)
    {
        $this->tenant = $tenant;
        $this->landlord = $landlord;
        $this->unit = $unit;
        $this->property = $property;
        $this->address = $address;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your New Account on '.config('app.name'))
                    ->view('emails.account_password');
    }
}
