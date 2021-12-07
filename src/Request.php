<?php
namespace Voip;
use Voip\Endpoint;
use Voip\HttpClients\HttpClients;

class Request
{
    private $param;
    protected $getBody;
    const RequestRaw = '';
    public function __construct()
    {
        $this->HttpClients = new HttpClients();
    }
    public function RequestAuthticationToken($action = '', $param = array())
    {
        $this->RequestRaw = $this->HttpClients->RequestAuthtication(Endpoint::API_URL_AUTH.'/'.$action,
            $this->getBody($param));
        return $this->RequestRaw;
    }
    public function RequestGraph($accessToken = '', $action = '', $param = array())
    {
        $this->RequestRaw = $this->HttpClients->RequestGraph(Endpoint::API_URL_GRAPH.'/'.$action, $accessToken,
            $this->getBody($param));
        return $this->RequestRaw;
    }
    private function getBody($param = array())
    {
        return http_build_query($param, null, '&');
    }
}