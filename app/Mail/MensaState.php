<?php

namespace App\Mail;

use App\Models\Mensa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

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

        $personel = $this->mensa->users()->select(DB::raw('*, mensa_users.extra_info as uextra_info, mensa_users.allergies as uallergies, mensa_users.vegetarian as uvegetarian'))
            ->join('users', 'users.lidnummer', '=', 'mensa_users.lidnummer')
            ->where(function($query) {
                $query->where('mensa_users.cooks', '1')
                    ->orWhere('mensa_users.dishwasher', '1');
            })
            ->orderBy('mensa_users.cooks', 'DESC')
            ->orderBy('mensa_users.dishwasher', 'DESC')
            ->orderBy('users.name')
            ->orderBy('mensa_users.is_intro')->get();

        $personelIndex = 1;
        $cooks = $this->mensa->users()->where('cooks', '1')->count();
        $dishwashers = $this->mensa->dishwashers();
        $secondDishwasher = $dishwashers < 2 && $this->mensa->maxDishwashers() > 1;

        $guests = $this->mensa->users()->select(DB::raw('*, mensa_users.extra_info as uextra_info, mensa_users.allergies as uallergies, mensa_users.vegetarian as uvegetarian'))
            ->join('users', 'users.lidnummer', '=', 'mensa_users.lidnummer')
            ->where('mensa_users.cooks', '0')
            ->where('mensa_users.dishwasher', '0')
            ->orderBy('users.name')
            ->orderBy('mensa_users.is_intro')->get();


        return $this->view('emails.state.mensastate', [
            'personelIndex' => $personelIndex,
            'cooks' => $cooks,
            'dishwashers' => $dishwashers,
            'mensa' => $this->mensa,
            'secondDishwasher' => $secondDishwasher,
            'personel' => $personel,
            'guests' => $guests
        ]);
    }
}
