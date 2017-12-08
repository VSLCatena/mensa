<?php

namespace App\Http\Controllers;

use Adldap\Laravel\Facades\Adldap;
use App\Mail\SigninConfirmed;
use App\Mail\SigninConformation;
use App\Models\Mensa;
use App\Models\MensaExtraOption;
use App\Models\MensaUser;
use App\Models\User;
use App\Traits\LdapHelpers;
use App\Traits\Logger;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class SigninController extends Controller
{
    use LdapHelpers, Logger;

    public function mailSignin(Request $request, $userToken){
        return $this->signin($request, null, $userToken);
    }

    public function signin(Request $request, $id = null, $userToken = null){

        /* @var $mensaUser MensaUser */
        /* @var $introUser MensaUser */
        /* @var $mensa Mensa */
        $mensaUser = null;
        $introUser = null;
        $mensa = null;

        $shouldSendMail = true;
        $shouldSendVerificationMailInstead = false;

        if(Auth::check() && Auth::user()->mensa_admin && $id != null && $userToken != null){
            $request->session()->flash('asAdmin', 'true');
        }

        // We want to keep asAdmin and extra_lidnummer around
        if(Auth::check() && Auth::user()->mensa_admin && $request->session()->has('asAdmin')) {
            $request->session()->reflash();
            // Except the default warnings and such, it's annoying to have them go with us after redirecting
            $request->session()->remove('info');
            $request->session()->remove('warning');
            $request->session()->remove('error');
        } // The reason we're doing it here is so we don't have to do it with every error message we display

        // First we try if we can get the mensa by the user token
        if($userToken != null){
            try {
                // We want to create a query object to search for a certain signin
                /* @var $userQuery \Doctrine\DBAL\Query\QueryBuilder */
                $userQuery = MensaUser::where('confirmation_code', $userToken);
                // And ONLY if we're admin, we allow to do it with the id too
                if(Auth::check() && Auth::user()->mensa_admin){
                    $userQuery = $userQuery->orWhere('id', $userToken);
                }

                $mensaUser = $userQuery->firstOrFail();
                $mensa = $mensaUser->mensa;

                // If the selected user is an intro, we retrieve the main user and switch them around
                // We can also sign people in as intro without the person introing it having to be there
                // So make sure the main person exists
                if($mensaUser->is_intro && $mensaUser->mainUser != null){
                    $introUser = $mensaUser;
                    $mensaUser = $mensaUser->mainUser;
                }

                $shouldSendMail = false;
            } catch(ModelNotFoundException $e){
                return redirect(route('home'))->with('error', 'Inschrijving niet gevonden! Als je denkt dat dit een fout is neem dan contact op met '.config('mensa.contact.mail').'.');
            }
        } else {
            // If we can't get the mensa by the user token, we try to get it by the mensaId
            try {
                $mensa = Mensa::findOrFail($id);
            } catch(ModelNotFoundException $e){
                return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
            }
        }

        // We check if the Mensa isn't closed yet
        if($mensa->closed){
            $route = (Auth::check() && Auth::user()->mensa_admin) ?
                route('mensa.signins', ['id' => $mensa->id]) :
                route('home');
            return redirect($route)->with('error', 'Deze mensa is al gesloten!');
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

        // Same applies to this one, we create a new intro if none has been defined yet
        if(!$mensaUser->is_intro && $introUser == null){
            if($mensaUser->intros()->count() > 0){
                $introUser = $mensaUser->intros()->first();
            } else {
                $introUser = new MensaUser();

                $introUser->is_intro = true;
                $introUser->cooks = false;
                $introUser->paid = false;
                $introUser->confirmation_code = $mensaUser->confirmation_code;
                $introUser->mensa()->associate($mensa);
            }
        }


        // If method is get we want to just show the view
        if($request->isMethod('get')) {
            if ($mensaUser->id == null) {

                // We can give some feedback for the mensa is full
                if ($mensa->max_users <= $mensa->users()->count()) {
                    $request->session()->flash('warning', 'Deze mensa zit vol!');
                }

                // Are we requesting this page as an admin? Then we fill in the email we provided
                if (Auth::check() && Auth::user()->mensa_admin && $request->session()->has('asAdmin')) {
                    $user = $this->getLdapUserBy('description', session('extra_lidnummer'));
                    // We check if the user can be found in LDAP, and if not, we return back to the form with an error message
                    if($user == null){
                        $request->session()->flash('error', 'Lidnummer niet gevonden!');
                        return view('signin', compact('mensaUser', 'introUser'));
                    }
                    $mensaUser->user()->associate($user);
                } // If we are logged in, we can auto fill some info
                else if (Auth::check()) {
                    $mensaUser->user()->associate(Auth::user());
                    $mensaUser->allergies = Auth::user()->allergies;
                    $mensaUser->extra_info = Auth::user()->extra_info;
                } // Otherwise we associate an empty user to it
                else {
                    $mensaUser->user()->associate(new User());
                }
            }

            return view('signin', compact('mensaUser', 'introUser'));
        }

        // Since we know it is not a GET method now, we continue and sign the person in.

        // First we validate the request for the user itself
        $request->validate([
            'email' => 'email',
            'allergies' => 'max:191',
            'extrainfo' => 'max:191',
            'extra.*' => 'exists:mensa_extra_options,id',
        ]);

        // And if we have an intro validate those as well
        if($request->has('intro')){
            $request->validate([
                'intro_allergies' => 'max:191',
                'intro_extrainfo' => 'max:191',
                'intro_extra.*' => 'exists:mensa_extra_options,id',
            ]);
        }

        $user = null;
        if(Auth::check()){
            if(Auth::user()->mensa_admin && $request->session()->has('asAdmin')){
                $request->validate([
                    'lidnummer' => 'required|exists:users'
                ]);
                $user = User::findOrFail($request->input('lidnummer'));
                $mensaUser->user()->associate($user);
                $mensaUser->lidnummer = $user->lidnummer;
            } else {
                $user = Auth::user();
            }
        }

        // If the user gets signed in by an admin (from the admin panel) we just confirm the user
        if(Auth::check() && Auth::user()->mensa_admin && $request->session()->has('asAdmin')){
            $mensaUser->confirmed = true;

            $shouldSendMail = false;
            $shouldSendVerificationMailInstead = true;

            if($request->has('as_intro')){
                $mensaUser->is_intro = true;
                $shouldSendVerificationMailInstead = false;
            }
        }
        // If the user is logged in, depending if the email address is the same as the users', we automatically verify the user or not
        else if($user != null && $request->has('email') && strtolower($user->email) == strtolower($request->input('email'))){
            $mensaUser->confirmed = true;
            $mensaUser->lidnummer = $user->lidnummer;

            $shouldSendMail = false;
            $shouldSendVerificationMailInstead = true;
        }


        // If lidnummer doesn't exist yet, we will look in LDAP with the provided email
        if($mensaUser->lidnummer == null){
            $user = $this->getLdapUserBy('mail', $request->input('email'));
            // We check if the user can be found in LDAP, and if not, we return back to the form with an error message
            if($user == null){
                $request->flash();
                $mensaUser->user()->associate(new User());

                $request->session()->flash('error', 'Deze email is niet gevonden! Als je denkt dat dit een fout is, neem dan contact op met '.config('mensa.contact.mail').'.');
                return view('signin', compact('mensaUser'));
            }

            // Otherwise we update the mensa user
            $mensaUser->lidnummer = $user->lidnummer;
        }

        // If this is a new user, we want to check if the user already signed in previously or not
        // and if so (silly him), we just want to update the previous one
        if($mensaUser->id == null && !$mensaUser->is_intro) {
            /* @var $possibleDuplicate MensaUser */
            $possibleDuplicate = $mensa->users()
                ->where('lidnummer', $mensaUser->lidnummer)
                ->where('is_intro', '0')->first();

            if ($possibleDuplicate != null) {
                $mensaUser = $possibleDuplicate;

                // Check if we already had an intro
                if ($possibleDuplicate->intros()->count() > 0) {
                    $introUser = $possibleDuplicate->intros()->first();
                }

                $shouldSendMail = false;
                $shouldSendVerificationMailInstead = false;

                $request->session()->flash('warning', 'We hebben een oude inschrijving voor deze mensa van je gevonden, deze hebben we geupdatet!');
            } else {
                if (Auth::check() && Auth::user()->lidnummer == $mensaUser->lidnummer) {
                    $request->session()->flash('info', 'Je hebt jezelf succesvol ingeschreven!');
                } else {
                    $request->session()->flash('info', 'Inschrijving succesvol!');
                }
            }
        } else {
            $request->session()->flash('info', 'Inschrijving succesvol aangepast!');
        }


        // First we want to be sure that we aren't going over the maximum amount of signins
        if($mensaUser->id == null || $introUser == null || $introUser->id == null){
            $addedUsers = ($mensaUser->id==null?1:0) + (($request->has('intro') && $introUser->id==null)?1:0);
            // If the amount of users we currently have, plus the new users we are about to add
            // exceed the max users of a mensa, we want to cancel
            if($mensa->users()->count() + $addedUsers > $mensa->max_users){
                Input::flash();
                $request->session()->remove('info');
                $request->session()->remove('warning');
                $request->session()->flash('error', 'Deze mensa zit vol!');
                return view('signin', compact('mensaUser', 'introUser'));
            }
        }


        // We need to update late, because we might retrieve a new mensaUser when we find a duplicate
        $mensaUser->cooks = (Auth::check() && Auth::user()->mensa_admin) && ((bool)$request->has('cooks'));
        $mensaUser->dishwasher = (bool)$request->has('dishwasher');
        $mensaUser->vegetarian = (bool)$request->has('vegetarian');
        $mensaUser->allergies = $request->input('allergies');
        $mensaUser->extra_info = $request->input('extrainfo');

        // Log the singin
        if($mensaUser->id == null){
            $this->log($mensa,
                $mensaUser->user->name.
                ($mensaUser->confirmed?' is ingeschreven.':' heeft gereserveerd en moet zich nog bevestigen.'));
        } else {
            $this->log($mensa, 'De inschrijving van '.$mensaUser->user->name.' is aangepast.');
        }

        // And lastly we save the user
        $mensaUser->save();
        // Delete all previous extra options, having to update each one of them is just too much of a hassle
        $mensaUser->extraOptions()->detach();
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
            $introUser->dishwasher = $mensaUser->dishwasher;
            $introUser->vegetarian = (bool)$request->has('intro_vegetarian');
            $introUser->allergies = $request->input('intro_allergies');
            $introUser->extra_info = $request->input('intro_extrainfo');

            $introUser->save();
            $introUser->extraOptions()->detach();
            foreach($request->all('intro_extra') as $id){
                try {
                    $extraOption = $mensa->extraOptions()->findOrFail($id);
                    $introUser->extraOptions()->attach($extraOption);
                } catch(ModelNotFoundException $e){}
            }
        } else if(!$mensaUser->is_intro) {
            // If the user doesn't want to have intros, we just try to delete them, if he had any
            $mensaUser->intros()->delete();
        }


        // Do the sending stuffz
        if($shouldSendMail){
            Mail::to($mensaUser->user)->send(new SigninConformation($mensaUser));
        } else if($shouldSendVerificationMailInstead){
            Mail::to($mensaUser->user)->send(new SigninConfirmed($mensaUser));
        }


        $route = (Auth::check() && Auth::user()->mensa_admin) ?
            route('mensa.signins', ['id' => $mensa->id]) :
            route('home');
        return redirect($route);
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

        // Log the signout
        $this->log($mensa, Auth::user()->name.' is uitgeschreven.');

        return redirect(route('home'));
    }
}
