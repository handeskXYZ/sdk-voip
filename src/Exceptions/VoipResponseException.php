<?php
/**
 * Voip © 2019
 *
 */

namespace Voip\Exceptions;

use Voip\VoipResponse;
use Voip\Exceptions\VoipSDKException;
use Voip\Exceptions\VoipAuthenticationException;
use Voip\Exceptions\VoipAuthorizationException;
use Voip\Exceptions\VoipClientException;
use Voip\Exceptions\VoipServerException;
use Voip\Exceptions\VoipOtherException;
use Voip\Exceptions\VoipResponseException;
use Voip\Exceptions\VoipOAException;

/**
 * Class VoipResponseException
 *
 * @package Voip
 */
class VoipResponseException extends VoipSDKException {

    /**
     * @var VoipResponse The response that threw the exception.
     */
    protected $response;

    /**
     * @var array Decoded response.
     */
    protected $responseData;

    /**
     * Creates a VoipResponseException.
     *
     * @param VoipResponse     $response          The response that threw the exception.
     * @param VoipSDKException $previousException The more detailed exception.
     */
    public function __construct(VoipResponse $response, VoipSDKException $previousException = null) {
        $this->response = $response;
        $this->responseData = $response->getDecodedBody();
        $errorMessage = $this->get('message', 'Unknown error from Graph.');
        $errorCode = $this->get('code', -1);
        parent::__construct($errorMessage, $errorCode, $previousException);
    }

    /**
     * A factory for creating the appropriate exception based on the response from Graph.
     *
     * @param VoipResponse $response The response that threw the exception.
     *
     * @return VoipResponseException
     */
    public static function create(VoipResponse $response) {
        $data = $response->getDecodedBody();

        $code = isset($data['error']) ? $data['error'] : null;
        $message = isset($data['message']) ? $data['message'] : 'Unknown error from Graph.';

        switch ($code) {
            // Login status or token expired, revoked, or invalid
            case 100:
                return new static($response, new VoipClientException($message, $code));
            case 110:
                return new static($response, new VoipClientException($message, $code));
            case 111:
                return new static($response, new VoipClientException($message, $code));
            case 210:
                return new static($response, new VoipClientException($message, $code));
            case 289:
                return new static($response, new VoipClientException($message, $code));
            case 452:
                return new static($response, new VoipClientException($message, $code));
            case 2004:
                return new static($response, new VoipClientException($message, $code));
            case 2500:
                return new static($response, new VoipClientException($message, $code));
            case 10000:
                return new static($response, new VoipClientException($message, $code));
            case 10001:
                return new static($response, new VoipClientException($message, $code));
            case 10002:
                return new static($response, new VoipClientException($message, $code));
            case 10003:
                return new static($response, new VoipClientException($message, $code));
            case 10004:
                return new static($response, new VoipClientException($message, $code));
            case 12000:
                return new static($response, new VoipClientException($message, $code));
            case 12001:
                return new static($response, new VoipClientException($message, $code));
            case 12002:
                return new static($response, new VoipClientException($message, $code));
            case 12003:
                return new static($response, new VoipClientException($message, $code));
            case 12004:
                return new static($response, new VoipClientException($message, $code));
            case 12005:
                return new static($response, new VoipClientException($message, $code));
            case 12006:
                return new static($response, new VoipClientException($message, $code));
            case 12007:
                return new static($response, new VoipClientException($message, $code));
            case 12008:
                return new static($response, new VoipClientException($message, $code));
            case 12009:
                return new static($response, new VoipClientException($message, $code));
            case 12010:
                return new static($response, new VoipClientException($message, $code));
        }
        
        switch ($code) {
            case -201:
                return new static($response, new VoipOAException($message, $code));
            case -202:
                return new static($response, new VoipOAException($message, $code));
            case -204:
                return new static($response, new VoipOAException($message, $code));
            case -205:
                return new static($response, new VoipOAException($message, $code));
            case -207:
                return new static($response, new VoipOAException($message, $code));
            case -208:
                return new static($response, new VoipOAException($message, $code));
            case -209:
                return new static($response, new VoipOAException($message, $code));
            case -210:
                return new static($response, new VoipOAException($message, $code));
            case -211:
                return new static($response, new VoipOAException($message, $code));
            case -212:
                return new static($response, new VoipOAException($message, $code));
            case -213:
                return new static($response, new VoipOAException($message, $code));
            case -214:
                return new static($response, new VoipOAException($message, $code));
            case -215:
                return new static($response, new VoipOAException($message, $code));
            case -216:
                return new static($response, new VoipOAException($message, $code));
            case -217:
                return new static($response, new VoipOAException($message, $code));
            case -218:
                return new static($response, new VoipOAException($message, $code));
            case -219:
                return new static($response, new VoipOAException($message, $code));
            case -221:
                return new static($response, new VoipOAException($message, $code));
            case -305:
                return new static($response, new VoipOAException($message, $code));
            case -311:
                return new static($response, new VoipOAException($message, $code));
            case -320:
                return new static($response, new VoipOAException($message, $code));
            case -321:
                return new static($response, new VoipOAException($message, $code));
            case -20109:
                return new static($response, new VoipOAException($message, $code));
            case -20009:
                return new static($response, new VoipOAException($message, $code));
        }
        
        if ($code < 0) {
            return new static($response, new VoipOAException($message, $code));
        }
        // All others
        //return new static($response, new VoipOtherException($message, $code));
    }

    /**
     * Checks isset and returns that or a default value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    private function get($key, $default = null) {
        if (isset($this->responseData['error'][$key])) {
            return $this->responseData['error'][$key];
        }

        return $default;
    }

    /**
     * Returns the HTTP status code
     *
     * @return int
     */
    public function getHttpStatusCode() {
        return $this->response->getHttpStatusCode();
    }

    /**
     * Returns the sub-error code
     *
     * @return int
     */
    public function getSubErrorCode() {
        return $this->get('error_subcode', -1);
    }

    /**
     * Returns the error type
     *
     * @return string
     */
    public function getErrorType() {
        return $this->get('type', '');
    }

    /**
     * Returns the raw response used to create the exception.
     *
     * @return string
     */
    public function getRawResponse() {
        return $this->response->getBody();
    }

    /**
     * Returns the decoded response used to create the exception.
     *
     * @return array
     */
    public function getResponseData() {
        return $this->responseData;
    }

    /**
     * Returns the response entity used to create the exception.
     *
     * @return VoipResponse
     */
    public function getResponse() {
        return $this->response;
    }

}
