<?php
/**
 * Voip © 2019
 *
 */

namespace Voip\Authentication;

use Voip\Authentication\AccessToken;
use Voip\Authentication\AccessTokenMetadata;
use Voip\Voip;
use Voip\VoipApp;
use Voip\VoipRequest;
use Voip\VoipResponse;
use Voip\VoipClient;
use Voip\Exceptions\VoipResponseException;
use Voip\Exceptions\VoipSDKException;

/**
 * Class OAuth2Client
 *
 * @package Voip
 */
class OAuth2Client
{
    /**
     * @const string The base authorization URL.
     */
    const BASE_AUTHORIZATION_URL = 'https://oauth.Voipapp.com';

    /**
     * @const string Default OAuth API version for requests.
     */
    const DEFAULT_OAUTH_VERSION = 'v3';

    /**
     * The VoipApp entity.
     *
     * @var VoipApp
     */
    protected $app;

    /**
     * The Voip client.
     *
     * @var VoipClient
     */
    protected $client;

    /**
     * The last request sent to Graph.
     *
     * @var VoipRequest|null
     */
    protected $lastRequest;

    /**
     * @param VoipApp    $app
     * @param VoipClient $client
     * @param string|null    $graphVersion The version of the Graph API to use.
     */
    public function __construct(VoipApp $app, VoipClient $client)
    {
        $this->app = $app;
        $this->client = $client;
    }

    /**
     * Returns the last VoipRequest that was sent.
     * Useful for debugging and testing.
     *
     * @return VoipRequest|null
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * Generates an authorization URL to begin the process of authenticating a user.
     *
     * @param string $redirectUrl The callback URL to redirect to.
     * @param string $state       The CSPRNG-generated CSRF value.
     * @param array  $scope       An array of permissions to request.
     * @param array  $params      An array of parameters to generate URL.
     * @param string $separator   The separator to use in http_build_query().
     *
     * @return string
     */
    public function getAuthorizationUrl($redirectUrl, array $params = [], $separator = '&')
    {
        $params += [
            'app_id' => $this->app->getId(),
            'redirect_uri' => $redirectUrl,
        ];

        return static::BASE_AUTHORIZATION_URL . '/' . static::DEFAULT_OAUTH_VERSION . '/auth?' . http_build_query($params, null, $separator);
    }
    
    public function getAuthorizationUrlByPage($redirectUrl, array $params = [], $separator = '&')
    {
        $params += [
            'app_id' => $this->app->getId(),
            'redirect_uri' => $redirectUrl,
        ];
        return static::BASE_AUTHORIZATION_URL . '/' . static::DEFAULT_OAUTH_VERSION . '/oa/permission?'. http_build_query($params, null, $separator);
    }

    /**
     * Get a valid access token from a code.
     *
     * @param string $code
     * @param string $redirectUri
     *
     * @return AccessToken
     *
     * @throws VoipSDKException
     */
    public function getAccessTokenFromCode($code, $redirectUri = '')
    {
        $params = [
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ];

        return $this->requestAnAccessToken($params);
    }

    /**
     * Send a request to the OAuth endpoint.
     *
     * @param array $params
     *
     * @return AccessToken
     *
     * @throws VoipSDKException
     */
    protected function requestAnAccessToken(array $params)
    {
        $response = $this->sendRequestWithClientParams('/access_token', $params);
        $data = $response->getDecodedBody();

        if (!isset($data['access_token'])) {
            throw new VoipSDKException('Access token was not returned from Graph.', 401);
        }
        $expiresAt = 0;
        if (isset($data['expires'])) {
            $expiresAt = time() + $data['expires'];
        } elseif (isset($data['expires_in'])) {
            
            $expiresAt = time() + $data['expires_in'];
        }

        return new AccessToken($data['access_token'], $expiresAt);
    }
    protected function sendRequestWithClientParams($endpoint, array $params, $accessToken = null)
    {
        $params += $this->getClientParams();

        $accessToken = $accessToken ?: $this->app->getAccessToken();
        $url = static::BASE_AUTHORIZATION_URL . '/' . static::DEFAULT_OAUTH_VERSION . $endpoint;
        $this->lastRequest = new VoipRequest(
            $accessToken,
            'GET',
            $url,
            $params,
            null
        );

        return $this->client->sendRequest($this->lastRequest);
    }
    protected function getClientParams()
    {
        return [
            'app_id' => $this->app->getId(),
            'app_secret' => $this->app->getSecret(),
        ];
    }
}
