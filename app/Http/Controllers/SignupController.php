<?php

namespace App\Http\Controllers;

use Adldap\Laravel\Facades\Adldap;
use App\Models\Mensa;
use App\Models\MensaExtraOption;
use App\Models\MensaUser;
use App\Models\User;
use App\Traits\LdapHelpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignupController extends Controller
{
    use LdapHelpers;

    public function signup(Request $request){
        try {
            $mensa = Mensa::findOrFail($request->input('id'));
        } catch(ModelNotFoundException $e){
            return redirect(route('home'));
        }

        // If email hasn't been filled in, we want to show the signup form
        if(!$request->has('email')){
            if(Auth::check()){
                $user = Auth::user();
            } else {
                $user = new User();
            }
            return view('signup', compact('user', 'mensa'));
        }

        // Else we continue and sign the person in.
        // Depending if the email address is the same as the user logged in or not, we automatically verify the user or not

        $request->validate([
            'email' => 'required|email',
            'extra.*' => 'exists:mensa_extra_options,id',
        ]);

        $user = null;
        if(Auth::check()){
            $user = Auth::user();
        }
        $lidnummer = null;

        $mensaUser = new MensaUser();
        $mensaUser->cooks = false;
        $mensaUser->dishwasher = (bool)$request->has('dishwasher');
        $mensaUser->is_intro = false;
        $mensaUser->allergies = $request->input('allergies');
        $mensaUser->wishes = $request->input('wishes');
        $mensaUser->paid = false;
        $mensaUser->confirmed = false;

        if($user != null && strtolower($user->email) == strtolower($request->input('email'))){
            $mensaUser->confirmed = true;
            $mensaUser->lidnummer = $user->lidnummer;
        }

        if($lidnummer == null){
            $user = $this->getLdapUserBy('mail', $request->input('email'));
            $mensaUser->lidnummer = $user->lidnummer;
            if($user == null){
                // TODO ERROR! email couldn't be found!
            }
            // TODO send email
        }

        $mensaUser->mensa()->associate($mensa);
        $mensaUser->save();

        foreach($request->all('extra') as $id){
            $mensaUser->extraOptions()->attach($id);
        }
        
        return redirect(route('home'));
    }

    public function signout(Request $request){
        if(!Auth::check()){
            return redirect(route('home'));
        }

        try {
            $mensa = Mensa::findOrFail($request->input('id'));
        } catch(ModelNotFoundException $e){
            return redirect(route('home'));
        }

        $mensa->users()->where('lidnummer', Auth::user()->lidnummer)->delete();
        return redirect(route('home'));
    }
}
