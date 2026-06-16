<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RepairUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;
    public $ref;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tenant, $ref)
    {
        $this->tenant = $tenant;
        $this->ref = $ref;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Service Request Update")
                    ->view('emails.repair');
    }
}
