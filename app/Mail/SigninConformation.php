<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SigninConformation extends Mailable
{
    use Queueable, SerializesModels;

    private $mensaUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mensaUser)
    {
        $this->mensaUser = $mensaUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.signin.confirm', ['mensaUser' => $this->mensaUser]);
    }
}