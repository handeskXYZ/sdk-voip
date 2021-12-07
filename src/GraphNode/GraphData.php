<?php
namespace Voip\GraphNode;
use Voip\Exceptions\VoipSDKException;

class GraphData
{
    public $getData;
    public function __construct($getData = array())
    {
        $this->getData = $getData;
    }
    public function getData()
    {
        try{
            if(isset($this->getData->Object['data']) && !empty($this->getData->Object['data'])){
                return $this->getData->Object['data'];
            }else{
                throw new VoipSDKException('Data error', 401);
            }
        }catch(\Exception $e){
            return array();
        }
    }
}