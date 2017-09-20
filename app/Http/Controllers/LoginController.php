<?php

namespace App\Http\Controllers;

use Adldap\Auth\BindException;
use Adldap\Laravel\Facades\Adldap;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
        // If we're already logged in we redirect back to the homepage
        if(Auth::check())
            return redirect(route('home'));

        // With a get request we'll show the login page.
        if($request->isMethod('get'))
            return view('login');

        // Yay! LDAP login stuff!
        try {
            // First we bind to the user to check if we can actually sign in
            Adldap::connect('default', $request->input('username'), $request->input('password'));
        } catch(BindException $e){
            return view('login', [ 'msg' => $e->getMessage() ]);
        }

        // Then we grab the user object from LDAP using the samaccountname
        $user = Adldap::search()->findBy('samaccountname', $request->input('username'));
        if($user == null){
            return view('login', ['msg' => 'Something went wrong, contact the administrator for more information (samaccountname not found)']);
        }

        // We check if the user is in the ADLDAP allowed group that we defined in the .env file
        if(!in_array(env('ADLDAP_ALLOWED_GROUP', ''), $user->memberof)){
            return view('login', ['msg' => 'Something went wrong, contact the administrator for more information (not found in allowed group)']);
        }

        // Now we know for sure that the user is allowed to log in
        $dbUser = null;
        try {
            // We first check if we already have this user in our database
            $dbUser = User::findOrFail($user->description[0]);
        } catch(ModelNotFoundException $e){
            // If not, we create a new user
            $dbUser = new User();
            $dbUser->lidnummer = $user->description[0];
        }

        // We update all the information of the user
        $dbUser->name = $user->displayname[0];
        $dbUser->email = $user->mail[0];

        // Check if the user is a mensa admin
        $dbUser->mensa_admin = in_array(env('ADLDAP_ADMIN_GROUP', ''), $user->memberof);

        // Save it back to the database
        $dbUser->save();

        // And we log in with the found/newly created user
        Auth::login($dbUser, $request->has('remember'));

        // And redirect to home
        return redirect(route('home'));
    }

    public function logout(){
        Auth::logout();
        return redirect(route('home'));
    }
}
