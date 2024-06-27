<?php
namespace App\Helpers\MSGraphAPI;


use Microsoft\Graph\Graph;
use GuzzleHttp;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class User {
    protected $employeeNumberProperty ;
    protected $descriptionProperty ;
    protected $mailProperty ;
    protected $RoleAdminId;
    protected $RoleUserId;


    public $id;
    public $displayName;
    public $surname;
    public $givenName;
    public $description;
    public $userPrincipalName;
    public $email;
    public $onPremisesSamAccountName;
    public $employeeId;
    public $employeeNumber;
    public $memberOf;
    public $isAdmin;

    public function __construct()
    {
        $extension_app_id = Str::remove('-', config('service.azure.extension_app_id'));
        $this->employeeNumberProperty = 'extension_' . $extension_app_id . '_employeeNumber';
        $this->descriptionProperty = 'extension_' . $extension_app_id . '_description';
        $this->mailProperty = 'extension_' . $extension_app_id . '_mail';
    }



    public function getUserInfo($token) {
        if ($token) {

            $graph = new Graph();
            $graph->setApiVersion("beta");
            $graph->setAccessToken($token);


            $endpoint = '/me';
            $url = $endpoint . '?$count=true&$orderBy=displayName&$select=id,displayName,surname,givenName,userPrincipalName,mail,onPremisesSamAccountName,employeeId,' . $this->descriptionProperty . ',' . $this->employeeNumberProperty  . ',' . $this->mailProperty ;

            $request = $graph->createRequest("GET", $url);
            $request->addHeaders(["ConsistencyLevel"=> "eventual"]);
            $data=$request->execute();
            $content = $data->getBody();

            $this->getApplicationRoles($token);
            $this->getUserRoleAssignments($token,$content['id']);

            $this->id = $content['id'];
            $this->displayName = $content['displayName'];
            $this->surname = $content['surname'];
            $this->givenName = $content['givenName'];
            $this->userPrincipalName = $content['userPrincipalName'];
            $this->description = Arr::has($content,$this->descriptionProperty) ? Arr::join( $content[$this->descriptionProperty] , ',' ) : null;
            $this->email = $content[$this->mailProperty];
            $this->onPremisesSamAccountName = $content['onPremisesSamAccountName'];
            $this->employeeId = $content['employeeId'];
            $this->employeeNumber = $content[$this->employeeNumberProperty];

        }
    }

    private function getUserMemberOf($token) {

        if ($token) {
            $graph = new Graph();
            $graph->setApiVersion("beta");
            $graph->setAccessToken($token);
            $endpoint = "/me/transitiveMemberOf";
            $url = $endpoint . '?$count=true&$orderBy=displayName&$select=id,displayName,onPremisesSamAccountName,description,mail,groupType';

            //prepare request
            $request = $graph->createRequest("GET", $url);
            $request->addHeaders(["ConsistencyLevel"=> "eventual"]);
            //send request
            $response = $request->execute();
            //parse response
            $content = $response->getBody();
            //prepare output
            $memberOfObject['count']=$content['@odata.count'] ?? null;
            //loop over content
            foreach ($content['value'] as $key => $val) {
                $description = Arr::has($val,$this->descriptionProperty) ? Arr::join( $val[$this->descriptionProperty] , ',' ) : null;
                $memberOfObject['groups'][] = [
                    'objectType' => Str::remove('#microsoft.graph.', $val['@odata.type']) ?? null,
                    'id' => $val['id'],
                    'displayName' => $val['displayName'] ?? null,
                    'onPremisesSamAccountName' => $val['onPremisesSamAccountName'] ?? null ,
                    'description' => $description ?? null,
                    'mail' => $val['mail'] ?? null,
                ];
            }
            return $memberOfObject;
        }
    }


    private function getApplicationRoles($token) {

        if ($token) {
            $graph = new Graph();
            $graph->setApiVersion("beta");
            $graph->setAccessToken($token);
            # This is the unique ID of the service principal object associated with this application. This ID can be useful when performing management operations against this application using PowerShell or other programmatic interfaces.    
            # https://graph.microsoft.com/v1.0/servicePrincipals/12345/appRoleAssignedTo            
            # https://graph.microsoft.com/v1.0/servicePrincipals/12345?$select=appRoles
            $endpoint = '/servicePrincipals/' . config('service.azure.serviceprincipal_id'); 
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
                        case config('service.azure.role.admin'):
                            $this->RoleAdminId = $val['id']; break;
                        case config('service.azure.role.user'):
                            $this->RoleUserId = $val['id']; break;
                    }
                }
            }
        }
    }
    private function getUserRoleAssignments($token,$userId) {

        if ($token) {
            $graph = new Graph();
            $graph->setApiVersion("beta");
            $graph->setAccessToken($token);
            # This is the unique ID of the service principal object associated with this application. This ID can be useful when performing management operations against this application using PowerShell or other programmatic interfaces.               
            # https://graph.microsoft.com/v1.0/servicePrincipals/123456?$select=appRoles
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
                if($val['resourceId'] == config('service.azure.serviceprincipal_id')) {
                    switch($val['appRoleId']){
                        case $this->RoleUserId:
                            $this->isAdmin=False;break;
                        case $this->RoleAdminId:
                             $this->isAdmin=True;break;
                    }
                }
            }
        }
    }
}
