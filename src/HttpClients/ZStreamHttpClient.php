<?php
/**
 * Voip © 2019
 *
 */

namespace Voip\HttpClients;

use Voip\Http\GraphRawResponse;
use Voip\Exceptions\VoipSDKException;
use Voip\HttpClients\VoipHttpClientInterface;

/**
 * Class VoipStreamHttpClient
 *
 * @package Voip
 */
class VoipStreamHttpClient implements VoipHttpClientInterface
{
    /**
     * @var VoipStream Procedural stream wrapper as object.
     */
    protected $VoipStream;

    /**
     * @param VoipStream|null Procedural stream wrapper as object.
     */
    public function __construct(VoipStream $VoipStream = null)
    {
        $this->VoipStream = $VoipStream ?: new VoipStream();
    }

    /**
     * @inheritdoc
     */
    public function send($url, $method, $body, array $headers, $timeOut)
    {
        $options = [
            'http' => [
                'method' => $method,
                'header' => $this->compileHeader($headers),
                'content' => $body,
                'timeout' => $timeOut,
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => true, // All root certificates are self-signed
                'cafile' => __DIR__ . '/certs/DigiCertHighAssuranceEVRootCA.pem',
            ],
        ];

        $this->VoipStream->streamContextCreate($options);
        $rawBody = $this->VoipStream->fileGetContents($url);
        $rawHeaders = $this->VoipStream->getResponseHeaders();

        if ($rawBody === false || empty($rawHeaders)) {
            throw new VoipSDKException('Stream returned an empty response', 660);
        }

        $rawHeaders = implode("\r\n", $rawHeaders);

        return new GraphRawResponse($rawHeaders, $rawBody);
    }

    /**
     * Formats the headers for use in the stream wrapper.
     *
     * @param array $headers The request headers.
     *
     * @return string
     */
    public function compileHeader(array $headers)
    {
        $header = [];
        foreach ($headers as $k => $v) {
            $header[] = $k . ': ' . $v;
        }

        return implode("\r\n", $header);
    }
}
