<?php
namespace App\Traits;

use App\Helpers\MSGraphAPI\UserByApp;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Created by VS Code.
 * User: Kipjr
 * Date: 2023-10-28
 * Time: 22:01
 */
trait AzureHelpers
{
    private function getAzureInfoBy($field, $value){
        $UserByApp=new UserByApp();
        return $UserByApp->getUsers(null,$field, $value,False)->first();
         
    }

    public function searchAzureUsers($name){
        if(!preg_match('/^[a-zA-Z0-9 _]+$/', $name)){
            return '';
        }
        $UserByApp=new UserByApp();
        $users = $UserByApp->getUsers(10,'displayName',$name,False);

        return $users->map(function ($user){
            return [
                'name' => $user->name,
                'lidnummer' => $user->lidnummer
            ];
        }, $users);
    }

    public function saveAzureUser($user){
        $dbUser = null;
        try {
            // We first check if we already have this user in our database
            $dbUser = User::findOrFail($user->description);
        } catch(ModelNotFoundException $e){
            // If not, we create a new user
            $dbUser = new User();
            $dbUser->lidnummer = $user->description;
        }

        // We update all the information of the user
        $dbUser->name = $user->name;
        $dbUser->email = $user->email;
        $dbUser->phonenumber = $user->phonenumber;

        // Check if the user is a mensa admin
        $UserByApp=new UserByApp();
        $checkUser = $UserByApp->getUsers(10,'description',$user->description,$checkAdmin=true)->first();
        $dbUser->mensa_admin = $checkUser->mensa_admin;
        // Save it back to the database
        $dbUser->save();

        // And return it so we can use it
        return $dbUser;
    }


    public function getAzureUserBy($field, $value){
        $AzureInfo = $this->getAzureInfoBy($field, $value);
        if($AzureInfo == null)
            return null;

        return $this->saveAzureUser($AzureInfo);
    }
}