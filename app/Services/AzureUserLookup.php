<?php
namespace App\Services;

use App\Contracts\RemoteUserLookup;
use App\Models\User;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Ramsey\Uuid\Uuid;

class AzureUserLookup extends RemoteUserLookup {
    private ?string $token = null;
    private ?Client $httpClient = null;

    /**
     * Gets an user from Azure
     *
     * @param string $userReference an username or email
     * @return ?User
     * @throws ClientExceptionInterface
     */
    function getUser(string $userReference): ?User {
        $userReference = strtolower($userReference);

        $userRegex = '/^[a-z0-9]+$/';

        if (!preg_match($userRegex, $userReference) && !filter_var($userReference, FILTER_VALIDATE_EMAIL)) {
            // given user is not a valid username or email address
            throw new \InvalidArgumentException("User is not a valid username or email");
        }

        $azureUser = $this->getRawAzureUser($userReference);
        if ($azureUser == null) return null;

        return $this->mapUser($azureUser);
    }

    /**
     * Maps a raw azure user to an actual model
     *
     * @param ?object $raw raw azure user object
     * @return User
     * @throws ClientExceptionInterface
     */
    private function mapUser(?object $raw): User {
        $userGroups = $this->getUserGroups($raw->id);

        return new User([
            'id' => $raw->id,
            'name' => $raw->displayName,
            'email' => $raw->mail,
            'mensa_admin' => in_array(config('mensa.remote_user.admin_group_id'), $userGroups),
            'remote_principal_name' => $raw->userPrincipalName,
        ]);
    }

    /**
     * Gets a raw azure user json object
     *
     * @param string $userReference
     * @return ?object a raw azure user object
     * @throws ClientExceptionInterface
     */
    private function getRawAzureUser(string $userReference): ?object {
        $emailSuffix = config('mensa.remote_user.email_suffix');
        $emailedUser = ends_with($userReference, $emailSuffix) ? $userReference : "$userReference$emailSuffix";


        $filter = urlencode("mail eq '$userReference' or userPrincipalName eq '$emailedUser'");
        $requestUrl = "https://graph.microsoft.com/v1.0/users?\$filter=$filter";

        $res = $this->httpClient()->get($requestUrl, $this->withAuthorizationHeader());

        $rawUsers = json_decode($res->getBody())->value;
        if (count($rawUsers) <= 0) return null;

        return $rawUsers[0];
    }

    /**
     * Returns a list of groups the user is in
     *
     * @param string $userId
     * @return array an array of groups the user is in
     * @throws ClientExceptionInterface
     */
    function getUserGroups(string $userId): array {
        if (!Uuid::isValid($userId)) {
            throw new \InvalidArgumentException("Invalid uuid");
        }

        $requestUrl = "https://graph.microsoft.com/v1.0/users/$userId/getMemberGroups";
        $res = $this->httpClient()->post($requestUrl, $this->withAuthorizationHeader([
            'json' => [ 'securityEnabledOnly' => false ],
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]));

        return json_decode($res->getBody())->value;
    }

    /**
     * Returns a cached http client
     *
     * @return Client
     */
    private function httpClient(): Client {
        if ($this->httpClient != null)
            return $this->httpClient;

        $this->httpClient = new Client();
        return $this->httpClient;
    }

    /**
     * Automatically adds an authorization header to the options array
     *
     * @param array $options
     * @return array the same options array but with an added authorization header
     * @throws ClientExceptionInterface
     */
    private function withAuthorizationHeader(array $options = []): array {
        $token = $this->getBearerToken();

        return array_merge(
            $options,
            [
                'headers' => array_merge(
                    $options->headers ?? [],
                    ['Authorization' => "Bearer $token" ]
                )
            ]
        );
    }

    /**
     * Create a new bearer token we can use to make authorized requests
     *
     * @return string
     * @throws ClientExceptionInterface
     */
    private function getBearerToken(): string {
        if ($this->token != null) return $this->token;

        $tenantId = config('services.azure.tenant_id');
        $clientId = config('services.azure.client_id');
        $clientSecret = config('services.azure.client_secret');
        $grantType = 'client_credentials';
        $scope = 'https://graph.microsoft.com/.default';

        $res = $this->httpClient()->post("https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token", [
            'form_params' => [
                'grant_type' => $grantType,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => $scope
            ]
        ]);

        $this->token = json_decode($res->getBody())->access_token;
        return $this->token;
    }
}