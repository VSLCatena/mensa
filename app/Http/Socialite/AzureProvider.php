<?php
namespace App\Http\Socialite;


use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class AzureProvider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'AZURE';

    /**
     * The base Azure Graph URL.
     *
     * @var string
     */
    protected $graphUrl = 'https://graph.microsoft.com/v1.0/me/';

    /**
     * The URL to grab all the users' groups
     * @var string
     */
    protected $groupsUrl = 'https://graph.microsoft.com/v1.0/me/transitiveMemberOf/microsoft.graph.group';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://login.microsoftonline.com/'.($this->config['tenant_id'] ?: 'common').'/oauth2/v2.0/authorize',
            $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://login.microsoftonline.com/'.($this->config['tenant_id'] ?: 'common').'/oauth2/v2.0/token';
    }

    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'form_params' => $this->getTokenFields($code),
        ]);

        $this->credentialsResponseBody = json_decode($response->getBody(), true);

        return $this->parseAccessToken($response->getBody());
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->graphUrl, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        $groups = $this->getHttpClient()->get($this->groupsUrl, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ]
        ]);


        return [
            'user' => json_decode($response->getBody(), true),
            'groups' => json_decode($groups->getBody(), true)
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        $userObj = $user['user'];
        $groupsObj = array_map(function($group) {
            return $group['id'];
        }, $user['groups']['value']);

        return (new User())->setRaw($user)->map([
            'id'    => $userObj['id'],
            'nickname' => null,
            'name' => $userObj['displayName'],
            'email' => $userObj['mail'],
            'groups' => $groupsObj,
            'avatar' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     * We also add the scope to read all groups
     */
    public function getScopes(): array {
        return array_merge(parent::getScopes(), ['https://graph.microsoft.com/GroupMember.Read.All']);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

    /**
     * Add the additional configuration key 'tenant' to enable the branded sign-in experience.
     *
     * @return array
     */
    public static function additionalConfigKeys()
    {
        return ['tenant_id'];
    }
}

