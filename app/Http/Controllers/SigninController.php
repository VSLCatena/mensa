<?php

namespace App\Http\Controllers;

use App\Mail\SigninCancelled;
use App\Mail\SigninConfirmed;
use App\Mail\SigninConformation;
use App\Models\Mensa;
use App\Models\MensaExtraOption;
use App\Models\MensaUser;
use App\Models\User;
use App\Traits\Logger;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class SigninController extends Controller
{
    use Logger;

    public function mailSignin(Request $request, $userToken){
        try {
            $mensaUser = MensaUser::where('confirmation_code', $userToken)->firstOrFail();
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Inschrijving niet gevonden!');
        }

        return $this->handleSignin($request, $mensaUser, $mensaUser->intros()->first());
    }

    public function newSignin(Request $request, $mensaId, $lidnummer = null){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        // We create a generic mensa user which we can later copy from
        $mensaUser = new MensaUser();
        $mensaUser->paid = false;
        $mensaUser->cooks = false;
        $mensaUser->is_intro = false;
        $mensaUser->confirmed = false;
        // We also generate a confirmation link, this will be used both for signing in and out
        $mensaUser->confirmation_code = bin2hex(random_bytes(32));
        // We associate the user to a mensa (or the other way around, not like it matters)
        $mensaUser->mensa()->associate($mensa);

        // If the user is a service user, we want to block it.
        if(Auth::check() && Auth::user()->service_user && $lidnummer == null){
            return redirect(route('home'))->with('error', 'Je kan jezelf niet inschrijven!');
        }

        // We can give some feedback for if the mensa is full
        if ($mensaUser->id == null && $mensa->max_users <= $mensa->users()->count()) {
            $request->session()->flash('warning', 'Deze mensa zit vol!');
        }

        $user = Auth::user();

        // If the user is a mensa admin, we allow him to sign someone else in with his lidnummer
        if($lidnummer != null && Auth::check() && Auth::user()->mensa_admin){
            try {
                $azureAppInfo = new azureAppInfo;
                $user = $AzureAppInfo->getAzureUserBy('description', $lidnummer);
                $request->session()->flash('extra_lidnummer', $lidnummer);
            } catch(ModelNotFoundException $e){
                return redirect(route('home'))->with('error', 'Persoon niet gevonden!');
            }
            $mensaUser->user()->associate($user);
            $mensaUser->confirmed = true;
            $mensaUser->allergies = $user->allergies;
            $mensaUser->extra_info = $user->extra_info;
        } else {
            $mensaUser->user()->associate(new User());
        }

        // If the signin is new we can fill in some default values like allergies and such
        if($user != null){
            $mensaUser->lidnummer = $user->lidnummer;
            $mensaUser->vegetarian = $user->vegetarian;
            $mensaUser->allergies = $user->allergies;
            $mensaUser->extra_info = $user->extra_info;
        }


        // We replicate the mensa user, which makes everything easier for us
        $introUser = $mensaUser->replicate();
        $introUser->is_intro = true;


        if($request->isMethod('post')) {
            // If we already got POST data, we want to process some stuff
            // If we didn't provide a lidnummer but an email is provided, we want to check Azure
            if($lidnummer == null && $request->has('email')){
                $azureAppInfo = new azureAppInfo;
                $user = $azureAppInfo->getAzureUserBy('email', $request->input('email'));
                // We check if the user can be found in Azure, and if not, we return back to the form with an error message
                if($user == null){
                    $mensaUser->user()->associate(new User());
                    $request->session()->flash('error', 'Deze email is niet gevonden! Als je denkt dat dit een fout is, neem dan contact op met '.config('mensa.contact.mail').'.');
                    return view('signin', compact('mensaUser', 'introUser'));
                }

                $mensaUser->user()->associate($user);
                $introUser->user()->associate($user);

                // Otherwise we update the mensaUser and introUser
                $mensaUser->lidnummer = $user->lidnummer;
                $introUser->lidnummer = $user->lidnummer;
            }

            // If the person is logged in and the same user as we want to sign in we confirm it
            if(Auth::check() && Auth::user()->lidnummer == $mensaUser->lidnummer){
                $mensaUser->confirmed = true;
                $introUser->confirmed = true;
            }

            // Then we check if we haven't found a previous signin of the user
            /* @var $possibleDuplicate MensaUser */
            $possibleDuplicate = $mensa->users()
                ->where('lidnummer', $mensaUser->lidnummer)
                ->where('is_intro', '0')->first();

            if ($possibleDuplicate != null) {
                $mensaUser = $possibleDuplicate;
                $mensaUser->setCreatedAt(Carbon::now());
                $mensaUser->restore();

                // Check if we already had an intro
                if ($possibleDuplicate->intros()->count() > 0) {
                    $introUser = $possibleDuplicate->intros()->first();
                }

                $request->session()->flash('warning', 'We hebben een oude inschrijving voor deze mensa van je gevonden, deze hebben we geupdatet!');
            } // We want to be sure that we aren't going over the maximum amount of signins
            else {
                $addedUsers = ($mensaUser->id == null ? 1 : 0) + (($request->has('intro') && $introUser->id == null) ? 1 : 0);
                // If the amount of users we currently have, plus the new users we are about to add
                // exceed the max users of a mensa, we want to cancel
                if ($mensa->users()->count() + $addedUsers > $mensa->max_users) {
                    Input::flash();
                    $request->session()->flash('error', 'Deze mensa zit vol!');
                    return view('signin', compact('mensaUser', 'introUser'));
                } else {
                    if(Auth::check() && $mensaUser->lidnummer == Auth::user()->lidnummer){
                        $request->session()->flash('info', 'Je hebt jezelf succesvol ingeschreven!');
                    } elseif(!$mensaUser->confirmed){
                        $request->session()->flash('info',
                            'We hebben voor verificatie een bevestigingsmailtje gestuurd naar het opgegeven emailadres. '.
                            'Zorg dat je deze binnen 15 minuten bevestigt!');
                    } else {
                        $request->session()->flash('info', 'Persoon succesvol ingeschreven! We hebben een bevestigingsmailtje gestuurd naar het opgegeven emailadres.');
                    }
                }
            }
        }


        return $this->handleSignin($request, $mensaUser, $introUser);
    }


    public function editSignin(Request $request, $mensaId, $mensaUserId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
            $mensaUser = $mensa->users()->where('id', $mensaUserId)->firstOrFail();
            $request->session()->flash('extra_lidnummer', $mensaUser->user->lidnummer);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Inschrijving niet gevonden!');
        }

        if(!Auth::check() || (Auth::user()->lidnummer != $mensaUser->user->lidnummer && !Auth::user()->mensa_admin)){
            return redirect(route('home'))->with('error', 'Je mag deze inschrijving niet wijzigen!');
        }

        return $this->handleSignin($request, $mensaUser, $mensaUser->intros()->first());
    }


    /* @var $mensa Mensa */
    /* @var $mensaUser MensaUser */
    /* @var $introUser MensaUser */
    private function handleSignin(Request $request, $mensaUser, $introUser = null){
        $mensa = $mensaUser->mensa;
        // We check if the Mensa isn't closed yet
        if($mensa->closed || $mensa->isClosed() && !(Auth::check() && Auth::user()->mensa_admin)){
            $route = (Auth::check() && Auth::user()->mensa_admin) ?
                route('mensa.signins', ['mensaId' => $mensa->id]) :
                route('home');
            return redirect($route)->with('error', 'Deze mensa is al gesloten!');
        }

        // If method is get we want to just show the view
        if($request->isMethod('get')) {
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


        // We need to update late, because we might retrieve a new mensaUser when we find a duplicate
        $mensaUser->cooks = (Auth::check() && Auth::user()->mensa_admin) && ((bool)$request->has('cooks'));
        $mensaUser->dishwasher = (bool)$request->has('dishwasher');
        $mensaUser->vegetarian = (bool)$request->has('vegetarian');
        $mensaUser->allergies = $request->input('allergies');
        $mensaUser->extra_info = $request->input('extrainfo');

        // Log the signin
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
            $introUser->confirmation_code = $mensaUser->confirmation_code;
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
        if($mensaUser->user->email != null){
            if(!$mensaUser->confirmed){
                Mail::to($mensaUser->user)->send(new SigninConformation($mensaUser));
            } else {
                Mail::to($mensaUser->user)->send(new SigninConfirmed($mensaUser));
            }
        }

        $route = (Auth::check() && Auth::user()->mensa_admin) ?
            route('mensa.signins', ['mensaId' => $mensa->id]) :
            route('home');
        return redirect($route);
    }

    public function signout(Request $request){
        if(!Auth::check()){
            return redirect(route('home'));
        }

        try {
            $mensa = Mensa::findOrFail($request->input('id'));
            $mensaUser = $mensa->users()->where('lidnummer', Auth::user()->lidnummer)->firstOrFail();
        } catch(ModelNotFoundException $e){
            return redirect(route('home'));
        }

        // We don't want people to be able to sign out even after mensa is closed. This is annoying for the cooks
        // when they have already bought stuff with their budget.
        if($mensa->isClosed()){
            return redirect(route('home'))->with('error', 'Deze mensa is gesloten en je kan je hiervoor dus niet meer uitschrijven.');
        }

        // This is just a soft-delete. This is because we might need to retrieve information later.
        $mensaUser->intros()->delete();
        $mensaUser->delete();

        // Log the signout
        $this->log($mensa, Auth::user()->name.' is uitgeschreven.');

        // Send signout email
        if($mensaUser->user->email != null) {
            Mail::to($mensaUser->user)->send(new SigninCancelled($mensaUser));
        }

        $request->session()->flash('info', 'Je hebt jezelf succesvol uitgeschreven!');

        return redirect(route('home'));
    }
}
