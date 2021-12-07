<?php
namespace Voip;
use Voip\Exceptions\VoipSDKException;
use Voip\Voip;

class Response
{
    protected $body;
    protected $trace_id;
    protected $header;
    protected $getHttpcode;
    protected $getCode;
    protected $getMessage;
    protected $decodedBody;
    protected $object_to_array;
    public function __construct($request = '')
    {
        var_dump($request);
        $this->decodedBody = $request;
        $this->decodeBody();
        $this->getHeader();
    }
    public function getHttpcode()
    {
        $this->getHttpcode = $this->header['status'];
        return $this->getHttpcode;
    }
    public function getCode()
    {
        $this->getCode = $this->header['message']['code'];
        return $this->getCode;
    }
    public function getMessage()
    {
        $this->getMessage = $this->header['message']['message'];
        return $this->getMessage;
    }
    public function getHeader()
    {
        $this->header = $this->decodedBody->data;
        unset($this->header['response']);
        return $this->header;
    }
    public function getBody()
    {
        $this->body = @$this->decodedBody->data['response'];
        return $this->body;
    }
    public function getTrace()
    {
        $this->trace_id = $this->decodedBody->trace_id;
        return $this->trace_id;
    }
    private function decodeBody()
    {
        if(!isset($this->decodedBody) || empty($this->decodedBody)){
            throw new VoipSDKException('Request error', 401);
        }
        $this->array_to_object(json_decode($this->decodedBody, true));
    }
    private function array_to_object($array)
    {
        $this->decodedBody = (object)$array;
    }
}