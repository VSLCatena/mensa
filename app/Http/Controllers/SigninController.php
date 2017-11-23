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

class SigninController extends Controller
{
    use LdapHelpers;

    public function signin(Request $request, $id){
        try {
            $mensa = Mensa::findOrFail($id);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'));
        }

        // If method is get we want to just show the view
        if($request->isMethod('get')){
            $mensaUser = new MensaUser();
            $mensaUser->cooks = false;
            $mensaUser->dishwasher = false;
            $mensaUser->is_intro = false;
            $mensaUser->paid = false;
            $mensaUser->confirmed = false;
            $mensaUser->mensa()->associate($mensa);

            if(Auth::check() && Auth::user()->mensa_admin && $request->session()->has('asAdmin') && $request->session()->has('email')){
                $user = new User();
                $user->email = session('email');
                $mensaUser->user()->associate($user);
            } else if(Auth::check()){
                $mensaUser->user()->associate(Auth::user());
                $mensaUser->allergies = Auth::user()->allergies;
                $mensaUser->wishes = Auth::user()->wishes;
            } else {
                $mensaUser->user()->associate(new User());
            }

            return view('signin', compact('mensaUser'));
        }

        // Else we continue and sign the person in.
        // Depending if the email address is the same as the user logged in or not, we automatically verify the user or not

        // We validate the request
        $request->validate([
            'email' => 'required|email',
            'allergies' => 'max:191',
            'wishes' => 'max:191',
            'extra.*' => 'exists:mensa_extra_options,id',
        ]);

        // And if we have an intro validate those as well
        if($request->has('intro')){
            $request->validate([
                'intro_allergies' => 'max:191',
                'intro_wishes' => 'max:191',
                'intro_extra.*' => 'exists:mensa_extra_options,id',
            ]);
        }

        $user = null;
        if(Auth::check()){
            $user = Auth::user();
        }
        $lidnummer = null;

        // We create a new MensaUser object with some default values
        $mensaUser = new MensaUser();
        // We associate the user to a mensa (or the other way around, not like it matters)
        $mensaUser->mensa()->associate($mensa);
        $mensaUser->cooks = false;
        $mensaUser->dishwasher = (bool)$request->has('dishwasher');
        $mensaUser->is_intro = false;
        $mensaUser->allergies = $request->input('allergies');
        $mensaUser->wishes = $request->input('wishes');
        $mensaUser->paid = false;
        $mensaUser->confirmed = false;


        // Check if we're already logged in, and if so, link the user to it.
        if($user != null && strtolower($user->email) == strtolower($request->input('email'))){
            $mensaUser->confirmed = true;
            $mensaUser->lidnummer = $user->lidnummer;
        }

        // If the user gets signed in as admin we just confirm the user
        if($request->has('asAdmin') && $user != null && $user->mensa_admin){
            $mensaUser->confirmed = true;
        }

        // If not, we will look in LDAP
        if($lidnummer == null){
            $user = $this->getLdapUserBy('mail', $request->input('email'));
            if($user == null){
                $request->flash();
                $mensaUser->user()->associate(new User());

                $request->session()->flash('error', 'Deze email is niet gevonden! Als je denkt dat dit een fout is, neem dan contact op met '.env('MENSA_CONTACT_MAIL'));
                return view('signin', compact('mensaUser'));
            }

            $mensaUser->lidnummer = $user->lidnummer;

            if(!$mensaUser->confirmed){
                // TODO send email
            }
        }

        // And lastly we save the user
        $mensaUser->save();
        foreach($request->all('extra') as $id){
            try {
                $extraOption = $mensa->extraOptions()->findOrFail($id);
                $mensaUser->extraOptions()->attach($extraOption);
            } catch(ModelNotFoundException $e){}
        }

        // Here we check the intro stuff. Whoop whoop!
        if($request->has('intro')){
            $introUser = new MensaUser();
            $introUser->lidnummer = $mensaUser->lidnummer;
            $introUser->cooks = false;
            $introUser->dishwasher = (bool)$request->has('dishwasher');
            $introUser->is_intro = true;
            $introUser->allergies = $request->input('intro_allergies');
            $introUser->wishes = $request->input('intro_wishes');
            $introUser->paid = false;
            $introUser->confirmed = $mensaUser->confirmed;

            $introUser->mensa()->associate($mensa);
            $introUser->save();
            foreach($request->all('intro_extra') as $id){
                try {
                    $extraOption = $mensa->extraOptions()->findOrFail($id);
                    $introUser->extraOptions()->attach($extraOption);
                } catch(ModelNotFoundException $e){}
            }
        }

        if($user != null && $user->mensa_admin && $request->has('redirect')){
            return redirect($request->get('redirect'))->with('info', 'Gebruiker succesvol ingeschreven.');
        }

        // TODO give signup feedback
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
