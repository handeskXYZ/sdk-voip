<?php
namespace Voip\Authentication;
use Voip\Exceptions\VoipSDKException;

class OAth2
{
    protected $token;
    public function __construct($token = '')
    {
        $response = $token->getBody();
        $this->token = $response['data'];
    }
    public function isToken()
    {
        return $this->token['IsToken'];
    }
    public function isExpried()
    {
        return $this->token['Expried'];
    }
    public function isLonglive()
    {
        return $this->token['IsLonglive'];
    }
    public function isCreateAt()
    {
        return $this->token['Createat'];
    }
}