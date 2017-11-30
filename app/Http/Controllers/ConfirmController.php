<?php

namespace App\Http\Controllers;

use App\Mail\SigninConfirmed;
use App\Models\MensaUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;

class ConfirmController extends Controller
{
    public function confirm($code){
        try {
            $mensaUser = MensaUser::where('confirmation_code', $code)->firstOrFail();
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Inschrijving niet gevonden!');
        }

        if($mensaUser->confirmed){
            return redirect(route('home'))->with('error', 'Deze inschrijving is al bevestigd!');
        }

        $mensaUser->confirmed = true;
        $mensaUser->save();


        Mail::to($mensaUser->user)->send(new SigninConfirmed($mensaUser));

        return redirect(route('home'))->with('info', 'Inschrijving succesvol bevestigd!');
    }

    public function cancel($code){
        try {
            $mensaUser = MensaUser::where('confirmation_code', $code)->firstOrFail();
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Inschrijving niet gevonden!');
        }

        // This is just a soft-delete. This is because we might need to retrieve information later.
        $mensaUser->intros()->delete();
        $mensaUser->delete();

        // TODO Send email

        return redirect(route('home'))->with('info', 'Je hebt je succesvol uitgeschreven!');
    }
}
