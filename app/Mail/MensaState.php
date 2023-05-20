<?php

namespace App\Mail;

use App\Models\Mensa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MensaState extends Mailable
{
    use Queueable, SerializesModels;

    /* @var $mensa Mensa */
    private $mensa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    /* @param $mensa Mensa */
    public function __construct($mensa)
    {
        $this->mensa = $mensa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $staff = $this->mensa->staff();

        $staffIds = $staff->map(function ($item) {
            return $item->id;
        });

        $staffIndex = 1;
        $cooks = $this->mensa->cooks();
        $dishwashers = $this->mensa->dishwashers();
        $secondDishwasher = count($dishwashers) < 2 && $this->mensa->maxDishwashers() > 1;
        $singleDishwasherExtraConsumptions = $this->mensa->consumptions(false, true, true) - $this->mensa->consumptions(false, true);
        $countExtraOptions = $this->mensa->extraOptions()->count();

        $guests = $this->mensa->users(true)->whereNotIn('mensa_users.id', $staffIds)->get();

        return $this->view('emails.state.mensastate', [
            'staffIndex' => $staffIndex,
            'cooks' => $cooks,
            'dishwashers' => $dishwashers,
            'mensa' => $this->mensa,
            'secondDishwasher' => $secondDishwasher,
            'singleDishwasherExtraConsumptions' => $singleDishwasherExtraConsumptions,
            'countExtraOptions' => $countExtraOptions,
            'staff' => $staff,
            'guests' => $guests,
        ]);
    }
}
