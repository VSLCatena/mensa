<?php

namespace App\Http\Controllers;

use App\Models\Mensa;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignupController extends Controller
{
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

        

    }
}
