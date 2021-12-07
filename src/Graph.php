<?php
namespace Voip;
use Voip\Exceptions\VoipSDKException;
use Voip\Exceptions\HandleException;
use Voip\GraphNode\GraphTable;
use Voip\GraphNode\GraphData;
use Voip\Endpoint;
use Voip\Request;
use Voip\Response;

class Graph
{
    public $Response;
    protected $rawResponse;
    public $getMeta;
    public $getData;
    public $getBody;
    public $GraphTable;
    public $GraphData;
    public $getHttpcode;
    public function __construct()
    {
        $this->Request = new Request();
    }
    public function sendRequest($accessToken = '', $action = '', $param = array())
    {
        try{
            $this->rawResponse = $this->Request->RequestGraph($accessToken, $action, $param);
        }catch(\Exception $e){
            throw new VoipSDKException('Raw Response error', 401);
        }
        try{
            $this->Response = new Response($this->rawResponse);
        }catch(\Exception $e){
            throw new VoipSDKException('Request error', 401);
        }
    }
    protected function getMeta()
    {
        $this->getMeta = new HandleException($this->Response->getBody()['meta'], 'get Meta');
        return $this->getMeta;
    }
    protected function getData()
    {
        $this->getData = new HandleException($this->Response->getBody()['data'], 'get Data');
        return $this->getData;
    }
    protected function getBody()
    {
        $this->getBody = new HandleException($this->Response->getBody(), 'get Body');
        return $this->getBody;
    }
    public function getGraphTable()
    {
        $this->GraphTable = new GraphTable($this->getMeta(), $this->getData());
    }
    public function getGraphData()
    {
        $this->GraphData = new GraphData($this->getData());
    }
    public function getGraphMailBox()
    {
    }
    public function getGraphRecording()
    {
    }
    public function getGraphCall()
    {
    }
    public function getGraphPBX()
    {
    }
    public function getGraphOTP()
    {
    }
    public function getGraphAutocall()
    {
    }
    public function getGraphContact()
    {
    }
    public function getGraphSetting()
    {
    }
    public function getGraphMe()
    {
    }
}

