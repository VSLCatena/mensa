<?php
namespace App\Helpers\MSGraphAPI;


use Microsoft\Graph\Graph;
use GuzzleHttp;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class UserByApp {
    protected $employeeNumberProperty ;
    protected $descriptionProperty ;
    protected $mailProperty ;
    protected $RoleAdminId;
    protected $RoleUserId;

    public function __construct()
    {
        $extension_app_id = Str::remove('-', config('services.azure.extension_app_id'));
        $this->employeeNumberProperty = 'extension_' . $extension_app_id . '_employeeNumber';
        $this->descriptionProperty = 'extension_' . $extension_app_id . '_description';
        $this->mailProperty = 'extension_' . $extension_app_id . '_mail';
    }



    public function getUsers($topN=null,$searchProperty=null,$searchValue=null,$checkAdmin=False) {
        $token = $this->_token();
        if ($token) {
            if($checkAdmin){
                $this->getApplicationRoles();
            }
            $graph = new Graph();
            $graph->setApiVersion("v1.0");
            $graph->setAccessToken($token);
            // if request for max items then append to query
            $topN=$topN ? '&$top=' . $topN : null;
            // if request for extensionProperty then translate the propertyName
            switch($searchProperty){
                case 'employeeNumber':
                    $searchProperty = $this->employeeNumberProperty;
                    $search= $searchProperty && $searchValue ? '&$filter='. $searchProperty . ' eq \'' . $searchValue . '\'' : null;
                    break;
                case 'description':
                    $searchProperty = $this->descriptionProperty;
                    $search= $searchProperty && $searchValue ? '&$filter='. $searchProperty . ' eq \'' . $searchValue . '\'' : null;
                    break;
                case 'email':
                    $searchProperty = $this->mailProperty; 
                    $search= $searchProperty && $searchValue ? '&$filter='. $searchProperty . ' eq \'' . $searchValue . '\'' : null;
                    break;
                default:
                    $search = $searchProperty && $searchValue ? '&$search="'. $searchProperty . ':' . $searchValue . '"' : null;
                    break;
            }
            // if search then append to query
            //$search = $searchProperty && $searchValue ? '&$search="'. $searchProperty . ':' . $searchValue . '"' : null;
            //$search= $searchProperty && $searchValue ? '&$filter='. $searchProperty . ' eq \'' . $searchValue . '\'' : null;
            $endpoint = '/users';
            $url = $endpoint . '?$count=true&$orderBy=displayName' . $topN . $search . ' &$select=id,displayName,surname,givenName,userPrincipalName,mail,businessPhones,onPremisesSamAccountName,employeeId,' . $this->descriptionProperty . ',' . $this->employeeNumberProperty  . ',' . $this->mailProperty ;
            # https://graph.microsoft.com/v1.0/users?$search="displayName:MyName"&$count=true&$orderBy=displayName&$select=id,displayName,surname,givenName,userPrincipalName,mail,businessPhones,onPremisesSamAccountName,employeeId,extension_e1fd504a055643d5b9d370b8b24b45ee_description,extension_e1fd504a055643d5b9d370b8b24b45ee_employeeNumber,extension_e1fd504a055643d5b9d370b8b24b45ee_mail
            $request = $graph->createRequest("GET", $url);
            $request->addHeaders(["ConsistencyLevel"=> "eventual"]);
            $data=$request->execute();
            $content = $data->getBody();
            $users = collect(json_decode(json_encode($content['value']), FALSE));
            if(empty($users) || count($users) >= 50 ) { 
                return null; 
            }
            // if nextLink then loop
            while(array_key_exists('@odata.nextLink',$content)){
                $url = $content['@odata.nextLink'];
                $request = $graph->createRequest("GET", $url);
                $request->addHeaders(["ConsistencyLevel"=> "eventual"]);
                $data=$request->execute();
                $content = $data->getBody();
                $newusers = collect(json_decode(json_encode($content['value']), FALSE));
                $users = $users->merge($newusers);
            }
            
            $cleanupUsers = $users->map(function ($user) use($checkAdmin){
                if($checkAdmin){
                    $isAdmin = $this->getUserRoleAssignments($user->id);
                    $user->mensa_admin = $isAdmin ?? False;
                    
                }
                $user->name = $user->displayName;
                $user->email = $user->{$this->mailProperty} ?? null;
                $user->phonenumber = $user->businessPhones[0] ?? null;
                $user->description = isset($user->{$this->descriptionProperty}) ? Arr::join( $user->{$this->descriptionProperty} , ',' ) : null;
                $user->lidnummer = $user->{$this->employeeNumberProperty} ?? null;
                
                //cleanup object
                unset($user->displayName);
                unset($user->surname);
                unset($user->givenName);
                unset($user->employeeId);
                unset($user->userPrincipalName);
                unset($user->onPremisesSamAccountName);
                unset($user->mail);
                unset($user->businessPhones);
                unset($user->{$this->employeeNumberProperty});
                unset($user->{$this->descriptionProperty});
                unset($user->{$this->descriptionProperty . '@odata.type'});
                unset($user->{$this->mailProperty});

                return $user;
            }, $users);
            return $cleanupUsers;
            
        }
    }
    private function getUserRoleAssignments($userId) {
        $token = $this->_token();
        if ($token) {
            $graph = new Graph();
            $graph->setApiVersion("beta");
            $graph->setAccessToken($token);
            # This is the unique ID of the service principal object associated with this application. This ID can be useful when performing management operations against this application using PowerShell or other programmatic interfaces.               
            # https://graph.microsoft.com/v1.0/servicePrincipals/12345?$select=appRoles
            $endpoint = '/users/' . $userId . '/appRoleAssignments'; 
            $url = $endpoint . '?$select=resourceId,appRoleId,principalType';

            //prepare request
            $request = $graph->createRequest("GET", $url);
            //send request
            $response = $request->execute();
            //parse response
            $content = $response->getBody();
            //prepare output

            //loop over content
            foreach ($content['value'] as $key => $val) {
                if($val['resourceId'] == config('services.azure.serviceprincipal_id')) {
                    switch($val['appRoleId']){
                        case $this->RoleUserId:
                            return False;
                        case $this->RoleAdminId:
                            return True;
                        default:
                            return False;
                    }
                }
            }
            //return $userRoleAssignments;
        }
    }    
    private function getApplicationRoles() {
        $token = $this->_token();
        if ($token) {
            $graph = new Graph();
            $graph->setApiVersion("beta");
            $graph->setAccessToken($token);
            # This is the unique ID of the service principal object associated with this application. This ID can be useful when performing management operations against this application using PowerShell or other programmatic interfaces.    
            # https://graph.microsoft.com/v1.0/servicePrincipals/12345/appRoleAssignedTo            
            # https://graph.microsoft.com/v1.0/servicePrincipals/12456?$select=appRoles
            $endpoint = '/servicePrincipals/' . config('services.azure.serviceprincipal_id'); 
            $url = $endpoint . '?$select=appRoles';

            //prepare request
            $request = $graph->createRequest("GET", $url);
            //send request
            $response = $request->execute();
            //parse response
            $content = $response->getBody();
            //prepare output
            //loop over content
            foreach ($content['appRoles'] as $key => $val) {
                if($val['isEnabled'] == 'true'){
                    switch($val['value']){
                        case config('services.azure.role.admin_value'):
                            $this->RoleAdminId = $val['id']; break;
                        case config('services.azure.role.user_value'):
                            $this->RoleUserId = $val['id']; break;
                    }
                }
            }
        }
    }

    private function _token(){
        $config = config('services')['azure'];
        try {
            $requestParms = ['form_params' => [

                'client_id' => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'resource' => 'https://graph.microsoft.com/',
                'grant_type' => 'client_credentials',
            ]];

            $client = new GuzzleHttp\Client([
                'base_uri' => "https://login.microsoftonline.com/". $config['tenant'] .  "/oauth2/",
                'timeout'  => 2.0,
            ]);
            $response =  (string) $client->request('POST', "token?api-version=1.0", $requestParms)->getBody();
            $response = json_decode($response);
            if (isset($response->access_token)) {
                return $response->access_token;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            print_r($e->getMessage(), true);
            return false;
        };
    }

    public function getToken(){
        return $this->_token();
    }



}
