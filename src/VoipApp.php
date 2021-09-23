<?php
/**
 * Voip © 2019
 *
 */

namespace Voip;

use Voip\Authentication\AccessToken;
use Voip\Exceptions\VoipSDKException;

/**
 * Class VoipApp
 *
 * @package Voip
 */
class VoipApp implements \Serializable
{
    /**
     * @var string The app ID.
     */
    protected $id;

    /**
     * @var string The app secret.
     */
    protected $secret;

    /**
     * @var string The app callback url.
     */
    protected $urlCallback;

    /**
     * @param string $id
     * @param string $secret
     *
     * @throws VoipSDKException
     */
    public function __construct($id, $secret, $url)
    {
        if (!is_string($id)
          // Keeping this for BC. Integers greater than PHP_INT_MAX will make is_int() return false
          && !is_int($id)) {
            throw new VoipSDKException('The "app_id" must be formatted as a string since many app ID\'s are greater than PHP_INT_MAX on some systems.');
        }
        // We cast as a string in case a valid int was set on a 64-bit system and this is unserialised on a 32-bit system
        $this->id = (string) $id;
        $this->secret = $secret;
        $this->urlCallback = $url;
    }

    /**
     * Returns the app ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the app secret.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Returns the app secret.
     *
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->urlCallback;
    }

    /**
     * Returns an app access token.
     *
     * @return AccessToken
     */
    public function getAccessToken()
    {
        return new AccessToken($this->id . '|' . $this->secret);
    }

    /**
     * Serializes the VoipApp entity as a string.
     *
     * @return string
     */
    public function serialize()
    {
        return implode('|', [$this->id, $this->secret]);
    }

    /**
     * Unserializes a string as a VoipApp entity.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($id, $secret) = explode('|', $serialized);

        $this->__construct($id, $secret);
    }
}
