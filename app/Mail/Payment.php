<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Payment extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;
    public $landlord;
    public $unit;
    public $property;
    public $address;
    public $amount;
    public $payment;
    public $date;
    public $ref;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tenant, $landlord, $unit, $property, $address, $amount, $payment, $date, $ref)
    {
        $this->tenant = $tenant;
        $this->landlord = $landlord;
        $this->unit = $unit;
        $this->property = $property;
        $this->address = $address;
        $this->amount = $amount;
        $this->payment = $payment;
        $this->date = $date;
        $this->ref = $ref;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Payment Confirmation and Receipt")
                    ->view('emails.payment');
    }
}
