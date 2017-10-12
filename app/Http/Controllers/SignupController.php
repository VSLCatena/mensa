<?php

namespace App\Http\Controllers;

use Adldap\Laravel\Facades\Adldap;
use App\Models\Mensa;
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

        $user = null;
        if(Auth::check()){
            $user = Auth::user();
        }
        $lidnummer = null;

        $pivots = [];
        $pivots['cooks'] = false;
        $pivots['dishwasher'] = $request->has('dishwasher');
        $pivots['is_intro'] = false;
        $pivots['allergies'] = strip_tags($request->input('allergies'));
        $pivots['wishes'] = strip_tags($request->input('wishes'));
        $pivots['paid'] = false;
        $pivots['confirmed'] = false;

        if($user != null && strtolower($user->email) == strtolower($request->input('email'))){
            $pivots['confirmed'] = true;
            $lidnummer = $user->lidnummer;
        }

        if($lidnummer == null){
            $user = $this->getLdapUserBy('mail', $request->input('email'));

            if($user == null){
                // TODO ERROR! email couldn't be found!
            }
            // TODO send email
        }

        $mensa->users()->attach($lidnummer, $pivots);

        return redirect(route('home'));
    }
}
