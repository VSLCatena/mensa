<?php

namespace App\Http\Controllers;

use App\Models\MensaUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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


        // TODO Send email

        return redirect(route('home'))->with('info', 'Inschrijving succesvol bevestigd!');
    }

    public function cancel($code){
        try {
            $mensaUser = MensaUser::where('confirmation_code', $code)->firstOrFail();
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Inschrijving niet gevonden!');
        }

        $mensaUser->confirmed = false;
        // TODO how are we going to sign outs
        $mensaUser->save();


        // TODO Send email

        return redirect(route('home'))->with('info', 'Inschrijving succesvol bevestigd!');
    }

    public function edit($code){

    }
}
