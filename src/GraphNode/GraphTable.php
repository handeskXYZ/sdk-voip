<?php
namespace Voip\GraphNode;
class GraphTable
{
    protected $getMeta;
    protected $getData;
    public function __construct($getMeta = array(), $getData = array())
    {
        $this->getData = $getData;
        $this->getMeta = $getMeta;
    }
    public function getList()
    {
        try{
            if(isset($this->getData->Object['data'])){
                return $this->getData->Object['data'];
            }else{
                return $this->getData->Object;
            }
        }catch(\Exception $e){
            return array();
        }
    }
    public function getlimit()
    {
        try{
            return @$this->getMeta->Object['limit'];
        }catch(\Exception $e){
            return 0;
        }
    }
    public function getOffset()
    {
        try{
            return @$this->getMeta->Object['offset'];
        }catch(\Exception $e){
            return 0;
        }
    }
    public function getSort()
    {
        try{
            return @$this->getMeta->Object['sort'];
        }catch(\Exception $e){
            return 'DESC';
        }
    }
}