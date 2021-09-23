<?php
/**
 * Voip © 2019
 *
 */

namespace Voip;

use Voip\Exceptions\VoipSDKException;
use Voip\HttpClients\VoipCurlHttpClient;
use Voip\HttpClients\VoipHttpClientInterface;

/**
 * Class VoipClient
 *
 * @package Voip
 */
class VoipClient {

    /**
     * @const int The timeout in seconds for a normal request.
     */
    const DEFAULT_REQUEST_TIMEOUT = 60;

    /**
     * @var bool Toggle to use  beta url.
     */
    protected $enableBetaMode = false;

    /**
     * @var VoipHttpClientInterface HTTP client handler.
     */
    protected $httpClientHandler;

    /**
     * @var int The number of calls that have been made to .
     */
    public static $requestCount = 0;

    /**
     * Instantiates a new VoipClient object.
     *
     * @param VoipHttpClientInterface|null $httpClientHandler
     * @param boolean                          $enableBeta
     */
    public function __construct(VoipHttpClientInterface $httpClientHandler = null, $enableBeta = false) {
        $this->httpClientHandler = $httpClientHandler ? : $this->detectHttpClientHandler();
        $this->enableBetaMode = $enableBeta;
    }

    /**
     * Sets the HTTP client handler.
     *
     * @param VoipHttpClientInterface $httpClientHandler
     */
    public function setHttpClientHandler(VoipHttpClientInterface $httpClientHandler) {
        $this->httpClientHandler = $httpClientHandler;
    }

    /**
     * Returns the HTTP client handler.
     *
     * @return VoipHttpClientInterface
     */
    public function getHttpClientHandler() {
        return $this->httpClientHandler;
    }

    /**
     * Detects which HTTP client handler to use.
     *
     * @return VoipHttpClientInterface
     */
    public function detectHttpClientHandler() {
        return new VoipCurlHttpClient();
    }

    /**
     * Toggle beta mode.
     *
     * @param boolean $betaMode
     */
    public function enableBetaMode($betaMode = true) {
        $this->enableBetaMode = $betaMode;
    }

    /**
     * Prepares the request for sending to the client handler.
     *
     * @param VoipRequest $request
     *
     * @return array
     */
    public function prepareRequestMessage(VoipRequest $request) {
        $url = $request->getUrl();
        // If we're sending files they should be sent as multipart/form-data
        if ($request->containsFileUploads()) {
            $requestBody = $request->getMultipartBody();
            $request->setHeaders([
                'Content-Type' => 'multipart/form-data; boundary=' . $requestBody->getBoundary(),
            ]);
        } else if ($request->getMethod() === 'GET' || $request->isGraph() === true) {
            $requestBody = $request->getUrlEncodedBody();
            $request->setHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]);
        } else {
            $requestBody = $request->getRawBody();
            $request->setHeaders([
                'Content-Type' => 'application/json',
            ]);
        }
        return [
            $url,
            $request->getMethod(),
            $request->getHeaders(),
            $requestBody->getBody(),
        ];
    }

    /**
     * Makes the request to  and returns the result.
     *
     * @param VoipRequest $request
     *
     * @return VoipResponse
     *
     * @throws VoipSDKException
     */
    public function sendRequest(VoipRequest $request) {
        $request->validateAccessToken();

        list($url, $method, $headers, $body) = $this->prepareRequestMessage($request);
        // Since file uploads can take a while, we need to give more time for uploads
        $timeOut = static::DEFAULT_REQUEST_TIMEOUT;

        // Should throw `VoipSDKException` exception on HTTP client error.
        // Don't catch to allow it to bubble up.
        $rawResponse = $this->httpClientHandler->send($url, $method, $body, $headers, $timeOut);
        static::$requestCount++;

        $returnResponse = new VoipResponse(
                $request, $rawResponse->getBody(), $rawResponse->getHttpResponseCode(), $rawResponse->getHeaders()
        );

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;
    }

    /**
     * Makes the upload request to  and returns the result.
     *
     * @param VoipRequest $request
     *
     * @return VoipResponse
     *
     * @throws VoipSDKException
     */
    public function sendRequestUploadVideo(VoipRequest $request) {

        list($url, $method, $headers, $body) = $this->prepareUploadVideoRequestMessage($request);
        // Since file uploads can take a while, we need to give more time for uploads
        $timeOut = static::DEFAULT_REQUEST_TIMEOUT;

        // Should throw `VoipSDKException` exception on HTTP client error.
        // Don't catch to allow it to bubble up.
        $rawResponse = $this->httpClientHandler->send($url, $method, $body, $headers, $timeOut);
        static::$requestCount++;

        $returnResponse = new VoipResponse(
                $request, $rawResponse->getBody(), $rawResponse->getHttpResponseCode(), $rawResponse->getHeaders()
        );

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;
    }

    /**
     * Prepares the request for sending to the client handler.
     *
     * @param VoipRequest $request
     *
     * @return array
     */
    public function prepareUploadVideoRequestMessage(VoipRequest $request) {
        // If we're sending files they should be sent as multipart/form-data
        $requestBody = $request->getMultipartBody();
        $request->setHeaders([
            'Content-Type' => 'multipart/form-data; boundary=' . $requestBody->getBoundary(),
        ]);
        return [
            $request->getUrl(),
            $request->getMethod(),
            $request->getHeaders(),
            $requestBody->getBody(),
        ];
    }
}
