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
    public function getLdapInfoBy($field, $value){
        return Adldap::search()->findBy($field, $value);
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
        $dbUser->mensa_admin = $user->memberof != null && in_array(env('ADLDAP_ADMIN_GROUP', ''), $user->memberof);

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