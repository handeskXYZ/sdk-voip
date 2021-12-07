<?php
namespace Voip;
use Voip\Client;
use Voip\Response;
use Voip\Authentication\OAth2;

class Voip
{
    public $Response;
    public $oath2;
    protected $client;
    protected $app;
    protected $rawResponse;
    public function __construct(array $config = [])
    {
        $this->setDetectionHandler($config);
    }
    public function getAccessToken()
    {
        $this->rawResponse = $this->client->getAccessToken();
        $this->Response = new Response($this->rawResponse);
        $this->oath2 = new OAth2($this->Response);
    }
    private function setDetectionHandler($config)
    {
        $this->app = $config;
        $this->client = new Client($this->app);
    }
    private function getKey()
    {
        return $this->app['api_key'];
    }
    private function getSecert()
    {
        return $this->app['api_secert'];
    }
    public function getDebug($param = '')
    {
        echo '<pre>';
        var_dump($param);
        echo '</pre>';
    }
}