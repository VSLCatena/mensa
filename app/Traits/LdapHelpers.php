<?php
namespace App\Traits;

use Adldap\Auth\BindException;
use Adldap\Laravel\Facades\Adldap;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Created by PhpStorm.
 * User: SubSide
 * Date: 10/11/2017
 * Time: 6:01 PM
 */
trait LdapHelpers
{
    private function getLdapInfoBy($field, $value){
        return Adldap::search()
            ->in(config('mensa.ldap.user_base'))
            ->findBy($field, $value);
    }

    public function searchLdapUsers($name){
        if(!preg_match('/^[a-zA-Z0-9 _]+$/', $name)){
            return '';
        }
        $users = Adldap::search()->users()
            ->in(config('mensa.ldap.user_base'))
            ->rawFilter('(cn=*'.$name.'*)')
            ->whereHas('mail')
            ->limit(10)
            ->get();

        return $users->map(function ($user){
            return [
                'name' => $user->cn[0],
                'lidnummer' => $user->description[0]
            ];
        }, $users);
    }

    public function isLdapUser($username, $password){
        try {
            // We bind the user to check if we can actually sign in
            Adldap::connect('default', $username, $password);
            return true;
        } catch(BindException $e){
            return $e;
        }
    }

    public function saveLdapUser($user){
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
        $dbUser->name = $user->cn[0];
        $dbUser->email = $user->mail[0];
        $dbUser->phonenumber = $user->telephonenumber[0];

        // Check if the user is a mensa admin
        $dbUser->mensa_admin = $user->memberof != null && in_array(config('mensa.ldap.admin_group'), $user->memberof);

        // Save it back to the database
        $dbUser->save();

        // And return it so we can use it
        return $dbUser;
    }


    public function getLdapUserBy($field, $value){
        $ldapInfo = $this->getLdapInfoBy($field, $value);

        if($ldapInfo == null)
            return null;

        return $this->saveLdapUser($ldapInfo);
    }
}