<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Helpers\MSGraphAPI\User as AzureUserInfo;
use App\Helpers\MSGraphAPI\Application as AzureAppInfo;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
class LoginController extends Controller
{
    // /*
    // |--------------------------------------------------------------------------
    // | Login Controller
    // |--------------------------------------------------------------------------
    // |
    // | This controller handles authenticating users for the application and
    // | redirecting them to your home screen.
    // |
    // */


    public function login(Request $request){
        if($request->isMethod('get')){
            return view('login');
        }
        if($request->isMethod('post')){
            if(env('APP_ENV') == 'local' && env('APP_OFFLINE')==1 ){
                    $user=User::where('username',env('APP_DEBUG_USERNAME'))->first();
                    Auth::login($user, false);
                    return redirect(route('home'));
            } else {
            return Socialite::driver('azure')->with([
                'prompt'        => 'select_account',
                'whr'           =>config('service.azure.tenant_domain'),
                'domain_hint'   =>config('service.azure.tenant_domain')
                ])->redirect(route('home'));
            }
        }
    }


    public function callback() {
        $azureUser = Socialite::driver('azure')->user();
        try {
            $azureUserInfo =  new AzureUserInfo;
            $azureUserInfo->GetUserInfo($azureUser->token);
            /*
            App\Helpers\MSGraphAPI\User Object
            (
                [id] => USER_GUID
                [displayName] => FirstName LastName
                [surname] => LastName
                [givenName] => FirstName
                [description] => xx-yyy
                [userPrincipalName] => shortname@domain.nl
                [email] => real_email
                [onPremisesSamAccountName] => shortname
                [employeeId] => 123123123
                [employeeNumber] => xx-yyy
                [isAdmin] => //to be determined
                [isUser] =>  //to be determined
            )
            */      
            $azureAppInfo = new AzureAppInfo;
            $roles = $azureAppInfo->getAssignedRoles($azureUserInfo->id);
            $azureUserInfo->isUser=$roles['isUser'];
            $azureUserInfo->isAdmin=$roles['isAdmin'];
            
            User::upsert(
                [   'lidnummer' => $azureUserInfo->employeeNumber,
                    'email'     => $azureUserInfo->email,
                    'name'      => $azureUserInfo->displayName,
                ], ['lidnummer']
            );
            $user = User::where('lidnummer', $azureUserInfo->employeeNumber)->first();
            $user->mensa_admin = $azureUserInfo->isAdmin;
            $user->save();
            Auth::login($user);
        } catch (Exception $e) {
            return view('login', ['msg' => 'Something went wrong, contact '.config('mensa.contact.mail').' for more information']);
        }
        return redirect(route('home'));
    }

    public function loginByToken($token){
        $serviceUsers = config('mensa.service_users.users', []);

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
    
    public function logoutAzure(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->flush();
        $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('home'));
        return redirect($azureLogoutUrl);
    }

}
