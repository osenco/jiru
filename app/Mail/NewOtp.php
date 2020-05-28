<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOtp extends Mailable
{
    use Queueable, SerializesModels;
    public $otp;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp, $subject)
    {
        $this->otp     = $otp;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->subject($this->subject)
        ->markdown('emails.otp');
    }
}
