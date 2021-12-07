<?php
namespace Voip;
use Voip\Exceptions\VoipSDKException;
use Voip\Authentication\AccessToken;

class Client
{
    private $Authentication;
    private $param;
    public function __construct($param)
    {
        $this->param = $param;
        $this->doExceptionParam();
    }
    public function getAccessToken()
    {
        $this->RequestAuthentication();
        return $this->Authentication->getToken();
    }
    private function RequestAuthentication()
    {
        $this->Authentication = new AccessToken($this->param);
    }
    private function doExceptionParam()
    {
        if(!isset($this->param) || empty($this->param)){
            throw new VoipSDKException('Client Error load config', 401);
        }
    }
}