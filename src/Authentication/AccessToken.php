<?php
namespace Voip\Authentication;
use Voip\Exceptions\VoipSDKException;
use Voip\Endpoint;
use Voip\Request;
use Voip\Response;

class AccessToken
{
    private $ParamRequest;
    private $SecertApi;
    private $isToken;
    private $isExpired;
    private $Request;
    private $param;
    public function __construct(array $RequestAccess = [])
    {

        $this->ParamRequest = $RequestAccess;
        $this->Request = new Request();
        $this->setArrayToken();
    }
    public function getToken()
    {
        return $this->Request->RequestAuthticationToken(Endpoint::API_URL_TOKEN, $this->param);
    }
    private function setArrayToken()
    {
        $this->doExceptionHandler();
        $this->param = array(
            'api_key' => @$this->ParamRequest['api_key'],
            'api_secert' => @$this->ParamRequest['api_secert'],
        );
    }
    private function doExceptionHandler()
    {
        if(empty($this->ParamRequest)){
            throw new VoipSDKException('API_KEY missing', 401);
        }
        if(!isset($this->ParamRequest['api_key'])){
            throw new VoipSDKException('API_KEY missing', 401);
        }
        if(empty($this->ParamRequest['api_key'])){
            throw new VoipSDKException('API_KEY empty', 401);
        }
    }
}