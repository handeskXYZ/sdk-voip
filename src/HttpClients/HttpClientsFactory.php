<?php
/**
 * Voip © 2019
 *
 */

namespace Voip\HttpClients;

use Voip\HttpClients\VoipCurlHttpClient;

/**
 * Class HttpClientsFactory
 *
 * @package Voip
 */
class HttpClientsFactory {

    private function __construct() {
        // a factory constructor should never be invoked
    }

    /**
     * HTTP client generation.
     *
     * @param VoipHttpClientInterface|Client|string|null $handler
     *
     * @throws Exception               
     * @throws InvalidArgumentException If the http client handler isn't "curl", "stream", or an instance of Voip\HttpClients\VoipHttpClientInterface.
     *
     * @return VoipHttpClientInterface
     */
    public static function createHttpClient($handler) {
        if (!$handler) {
            return self::detectDefaultClient();
        }

        if ($handler instanceof VoipHttpClientInterface) {
            return $handler;
        }

        if ('curl' === $handler) {
            if (!extension_loaded('curl')) {
                throw new Exception('The cURL extension must be loaded in order to use the "curl" handler.');
            }

            return new VoipCurlHttpClient();
        }

        throw new InvalidArgumentException('The http client handler must be set to "curl" be an instance of Voip\HttpClients\VoipHttpClientInterface');
    }

    /**
     * Detect default HTTP client.
     *
     * @return VoipHttpClientInterface
     */
    private static function detectDefaultClient() {
        return new VoipCurlHttpClient();
    }

}
