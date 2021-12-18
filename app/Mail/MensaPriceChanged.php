<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MensaPriceChanged extends Mailable
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
        $this->subject('Mensa op ' . formatDate($this->mensaUser->mensa->date, false, false, false) . ' is gewijzigd!');
        return $this->markdown('emails.mensa.changed', ['mensaUser' => $this->mensaUser]);
    }
}
