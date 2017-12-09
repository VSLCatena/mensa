<?php

namespace App\Http\Controllers;

use Adldap\Auth\BindException;
use Adldap\Laravel\Facades\Adldap;
use App\Models\User;
use App\Traits\LdapHelpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use LdapHelpers;
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
        $canLogin = $this->isLdapUser($request->input('username'), $request->input('password'));
        if($canLogin !== true){
            return view('login', [ 'msg' => $canLogin->getMessage() ]);
        }

        // Then we grab the user object from LDAP using the samaccountname
        $user = $this->getLdapInfoBy('samaccountname', $request->input('username'));
        if($user == null){
            return view('login', ['msg' => 'Something went wrong, contact '.config('mensa.contact.mail').' for more information (samaccountname not found in base user dn)']);
        }

        // We check if the user is in the ADLDAP allowed group that we defined in the .env file
        if($user->memberof == null || !in_array(config('mensa.ldap.allowed_group'), $user->memberof)){
            return view('login', ['msg' => 'Something went wrong, contact '.config('mensa.contact.mail').' for more information (not found in allowed group)']);
        }

        $dbUser = $this->saveLdapUser($user);

        // And we log in with the found/newly created user
        Auth::login($dbUser, $request->has('remember'));

        // And redirect to home
        return redirect(route('home'));
    }

    public function loginByToken($token){
        $serviceUsers = config('mensa.service_users', []);

        foreach($serviceUsers as $serviceUser){
            if($token == $serviceUser['token']){
                try {
                    $user = User::findOrFail($serviceUser['lidnummer']);
                } catch(ModelNotFoundException $e){
                    $user = new User();
                    $user->lidnummer = $serviceUser['lidnummer'];
                }

                $user->service_user = true;
                $user->mensa_admin = true;
                $user->email = $serviceUser['lidnummer'];
                $user->name = $serviceUser['name'];
                $user->save();
                Auth::login($user, false);
            }
        }

        return redirect(route('home'));
    }


    public function logout(){
        if(Auth::check() && !Auth::user()->user_service) {
            Auth::logout();
        }
        return redirect(route('home'));
    }
}
