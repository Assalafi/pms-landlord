<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignUnit extends Mailable
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
        return $this->subject("You've Been Added to a New Property Unit")
                    ->view('emails.assign-unit');
    }
}
