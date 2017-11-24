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

    public function signin(Request $request, $id, $userToken = null){
        try {
            $mensa = Mensa::findOrFail($id);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        $mensaUser = null;
        if($userToken != null){
            try {
                // We want to create a query object to search for a certain signin
                $userQuery = MensaUser::where('is_intro', '0')->where('confirmation_token', $userToken);
                // And ONLY if we're admin, we allow to do it with the id too
                if(Auth::check() && Auth::user()->mensa_admin)
                    $userQuery = $userQuery->where('id', $userToken);

                $mensaUser = $userQuery->firstOrFail();
            } catch(ModelNotFoundException $e){
                return redirect(route('home'))->with('error', 'Inschrijving niet gevonden! Als dit een fout is neem dan contact op met '.env('MENSA_CONTACT_MAIL').'.');
            }
        }

        // If we aren't editing, we are creating!
        if($mensaUser == null){
            $mensaUser = new MensaUser();
            $mensaUser->paid = false;
            $mensaUser->cooks = false;
            $mensaUser->is_intro = false;
            $mensaUser->confirmed = false;
            // We also generate a confirmation link, this will be used both for signing in and out
            $mensaUser->confirmation_code = bin2hex(random_bytes(32));
            // We associate the user to a mensa (or the other way around, not like it matters)
            $mensaUser->mensa()->associate($mensa);
        }

        if($mensaUser->intros()->count() > 0){
            $introUser = $mensaUser->intros()->get(0);
        } else {
            $introUser = new MensaUser();

            $introUser->is_intro = true;
            $introUser->cooks = false;
            $introUser->paid = false;
            $introUser->confirmation_code = $mensaUser->confirmation_code;
            $introUser->mensa()->associate($mensa);
        }

        // If method is get we want to just show the view
        if($request->isMethod('get')){

            if(Auth::check() && Auth::user()->mensa_admin && $request->session()->has('extra_email')){
                $user = new User();
                $user->email = session('extra_email');
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

        $mensaUser->dishwasher = (bool)$request->has('dishwasher');
        $mensaUser->allergies = $request->input('allergies');
        $mensaUser->wishes = $request->input('wishes');


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
                // TODO Watch out that the user or an admin isn't just editing!
            }
        }

        // And lastly we save the user
        $mensaUser->save();
        $mensaUser->extraOptions()->delete();
        foreach($request->all('extra') as $id){
            try {
                $extraOption = $mensa->extraOptions()->findOrFail($id);
                $mensaUser->extraOptions()->attach($extraOption);
            } catch(ModelNotFoundException $e){}
        }

        // Here we check the intro stuff. Whoop whoop!
        if($request->has('intro')){ 
            $introUser->lidnummer = $mensaUser->lidnummer;
            $introUser->confirmed = $mensaUser->confirmed;
            $introUser->dishwasher = (bool)$request->has('dishwasher');
            $introUser->allergies = $request->input('intro_allergies');
            $introUser->wishes = $request->input('intro_wishes');

            $introUser->save();
            $introUser->extraOptions()->delete();
            foreach($request->all('intro_extra') as $id){
                try {
                    $extraOption = $mensa->extraOptions()->findOrFail($id);
                    $introUser->extraOptions()->attach($extraOption);
                } catch(ModelNotFoundException $e){}
            }
        }

        return redirect(route('home'))->with('info', 'Je hebt je succesvol ingeschreven!');
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
