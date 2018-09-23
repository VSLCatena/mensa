<?php

namespace App\Http\Controllers;

use App\Mail\SigninCancelled;
use App\Mail\SigninConfirmed;
use App\Models\MensaUser;
use App\Traits\Logger;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ConfirmController extends Controller
{
    use Logger;

    public function confirm($code){
        $mensaUsers = MensaUser::where('confirmation_code', $code)->get();
        if(count($mensaUsers) < 0){
            return redirect(route('home'))->with('error', 'Inschrijving niet gevonden!');
        }

        foreach($mensaUsers as $mensaUser) {
            if ($mensaUser->confirmed) {
                return redirect(route('home'))->with('error', 'Deze inschrijving is al bevestigd!');
            }

            $mensaUser->confirmed = true;
            $mensaUser->save();
        }
        // Log the confirmation
        if(count($mensaUsers) == 1){
            $this->log($mensaUser->mensa, $mensaUser->user->name.' heeft hun inschrijving bevestigd.');
        } else {
            $this->log($mensaUser->mensa, $mensaUser->user->name.' heeft '.count($mensaUsers).' inschrijvingen bevestigd.');
        }


        Mail::to($mensaUser->user)->send(new SigninConfirmed($mensaUser));

        return redirect(route('home'))->with('info', 'Inschrijving succesvol bevestigd!');
    }

    public function cancel($code){
        try {
            $mensaUser = MensaUser::where('confirmation_code', $code)->firstOrFail();
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Inschrijving niet gevonden!');
        }

        // We don't want people to be able to sign out even after mensa is closed. This is annoying for the cooks
        // when they have already bought stuff with their budget.
        if($mensaUser->mensa->isClosed()){
            return redirect(route('home'))->with('error', 'Deze mensa is gesloten en je kan je hiervoor dus niet meer uitschrijven.');
        }

        // This is just a soft-delete. This is because we might need to retrieve information later.
        $mensaUser->intros()->delete();
        $mensaUser->delete();

        // Log the cancellation
        $this->log($mensaUser->mensa, $mensaUser->user->name.' heeft zich uitgeschreven.');

        // Send signout email
        Mail::to($mensaUser->user)->send(new SigninCancelled($mensaUser));

        return redirect(route('home'))->with('info', 'Je hebt je succesvol uitgeschreven!');
    }
}
