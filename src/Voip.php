<?php
/**
 * Voip © 2019
 *
 */

namespace Voip;

use Voip\Authentication\AccessToken;
use Voip\Authentication\OAuth2Client;
use Voip\Authentication\VoipRedirectLoginHelper;
use Voip\Url\UrlDetectionInterface;
use Voip\Url\VoipUrlDetectionHandler;
use Voip\HttpClients\HttpClientsFactory;
use Voip\Exceptions\VoipSDKException;
use Voip\VoipClient;
use Voip\VoipRequest;

/**
 * Class Voip
 *
 * @package Voip
 */
class Voip
{
    /**
     * @const string Version number of the Voip PHP SDK.
     */
    const VERSION = '2.0.0';
    /**
     * @var VoipClient The Voip client service.
     */
    protected $client;
    /**
     * @var VoipApp The VoipApp entity.
     */
    protected $app;
    /**
     * @var UrlDetectionInterface|null The URL detection handler.
     */
    protected $urlDetectionHandler;
    /**
     * @var AccessToken|null The default access token to use with requests.
     */
    protected $defaultAccessToken;
    /**
     * @var VoipResponse|VoipBatchResponse|null Stores the last request made to Graph.
     */
    protected $lastResponse;
    /**
     * @var OAuth2Client The OAuth 2.0 client service.
     */
    protected $oAuth2Client;
    
    /**
     * Instantiates a new Voip super-class object.
     *
     * @param array $config
     *
     * @throws VoipSDKException
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'enable_beta_mode' => false,
            'http_client_handler' => 'curl',
            'url_detection_handler' => null,
        ], $config);
        $this->client = new VoipClient(
            HttpClientsFactory::createHttpClient($config['http_client_handler']),
            $config['enable_beta_mode']
        );
        $this->app = new VoipApp($config['app_id'], $config['app_secret'], $config['callback_url']);
        $this->setUrlDetectionHandler($config['url_detection_handler'] ?: new VoipUrlDetectionHandler());
        if (isset($config['default_access_token'])) {
            $this->setDefaultAccessToken($config['default_access_token']);
        }
    }
    /**
     * Returns the last response returned from Graph.
     *
     * @return VoipResponse|VoipBatchResponse|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }
    /**
     * Returns the URL detection handler.
     *
     * @return UrlDetectionInterface
     */
    public function getUrlDetectionHandler()
    {
        return $this->urlDetectionHandler;
    }
    /**
     * Changes the URL detection handler.
     *
     * @param UrlDetectionInterface $urlDetectionHandler
     */
    private function setUrlDetectionHandler(UrlDetectionInterface $urlDetectionHandler)
    {
        $this->urlDetectionHandler = $urlDetectionHandler;
    }
    /**
     * Returns the default AccessToken entity.
     *
     * @return AccessToken|null
     */
    public function getDefaultAccessToken()
    {
        return $this->defaultAccessToken;
    }
    /**
     * Sets the default access token to use with requests.
     *
     * @param AccessToken|string $accessToken The access token to save.
     *
     * @throws \InvalidArgumentException
     */
    public function setDefaultAccessToken($accessToken)
    {
        if (is_string($accessToken)) {
            $this->defaultAccessToken = new AccessToken($accessToken);
            return;
        }
        if ($accessToken instanceof AccessToken) {
            $this->defaultAccessToken = $accessToken;
            return;
        }
        throw new \InvalidArgumentException('The default access token must be of type "string" or Voip\AccessToken');
    }
    
    /**
     * Sends a GET request to Graph and returns the result.
     *
     * @param string                  $url
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return VoipResponse
     *
     * @throws VoipSDKException
     */
    public function get($url, $accessToken = null, array $params = [], $eTag = null)
    {   
        return $this->sendRequest(
            'GET',
            $url,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a POST request to Graph and returns the result.
     *
     * @param string                  $url
     * @param AccessToken|string|null $accessToken
     * @param array                   $params
     * @param string|null             $eTag
     *
     * @return VoipResponse
     *
     * @throws VoipSDKException
     */
    public function post($url, $accessToken = null, $params = [] , $eTag = null)
    {
        return $this->sendRequest(
            'POST',
            $url,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a POST request to Graph and returns the result.
     *
     * @param string                  $url
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return VoipResponse
     *
     * @throws VoipSDKException
     */
    public function uploadVideo($url, array $params = [], $accessToken = null, $eTag = null)
    {
        return $this->sendRequestUploadVideo(
            'POST',
            $url,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a DELETE request to Graph and returns the result.
     *
     * @param string                  $url
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return VoipResponse
     *
     * @throws VoipSDKException
     */
    public function delete($url, array $params = [], $accessToken = null, $eTag = null)
    {
        return $this->sendRequest(
            'DELETE',
            $url,
            $params,
            $accessToken,
            $eTag
        );
    }
    /**
     * Sends a request to Graph and returns the result.
     *
     * @param string                  $method
     * @param string                  $url
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return VoipResponse
     *
     * @throws VoipSDKException
     */
    public function sendRequest($method, $url, array $params = [], $accessToken = null, $eTag = null)
    {
        $request = $this->request($method, $url, $params, $accessToken, $eTag);
        return $this->lastResponse = $this->client->sendRequest($request);
    }
    /**
     * Sends a request upload video to OA and returns the result.
     *
     * @param string                  $method
     * @param string                  $url
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return VoipResponse
     *
     * @throws VoipSDKException
     */
    public function sendRequestUploadVideo($method, $url, array $params = [], $accessToken = null, $eTag = null)
    {
        $request = $this->request($method, $url, $params, $accessToken, $eTag);
        return $this->lastResponse = $this->client->sendRequestUploadVideo($request);
    }
    /**
     * Instantiates a new VoipRequest entity.
     *
     * @param string                  $method
     * @param string                  $url
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     * @param string|null             $eTag
     *
     * @return VoipRequest
     *
     * @throws VoipSDKException
     */
    public function request($method, $url, array $params = [], $accessToken = null, $eTag = null)
    {
        $request =  new VoipRequest(
            $accessToken,
            $method,
            $url,
            $params,
            $eTag
        );
        return $request;
    }
    /**
     * Returns the VoipApp entity.
     *
     * @return VoipApp
     */
    public function getApp()
    {
        return $this->app;
    }
    /**
     * Returns the VoipClient service.
     *
     * @return VoipClient
     */
    public function getClient()
    {
        return $this->client;
    }
    /**
     * Returns the OAuth 2.0 client service.
     *
     * @return OAuth2Client
     */
    public function getOAuth2Client()
    {
        if (!$this->oAuth2Client instanceof OAuth2Client) {
            $app = $this->getApp();
            $client = $this->getClient();
            $this->oAuth2Client = new OAuth2Client($app, $client);
        }
        return $this->oAuth2Client;
    }
    /**
     * Returns Login helper.
     *
     * @return VoipRedirectLoginHelper
     */
    public function getRedirectLoginHelper()
    {
        return new VoipRedirectLoginHelper(
            $this->getOAuth2Client(),
            $this->urlDetectionHandler
        );
    }
}