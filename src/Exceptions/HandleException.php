<?php
namespace Voip\Exceptions;
use Voip\Exceptions\VoipSDKException;

class HandleException
{
    public $Object;
    public function __construct($Object = '', $message = '')
    {
        $this->Handlle($Object, $message);
    }
    private function Handlle($Object = '', $message = '')
    {
        if(isset($Object) && !empty($Object)){
            try{
                $this->Object = $Object;
                return $this->Object;
            }catch(\Exception $e){
                throw new VoipSDKException('Response '.$message.' error', 401);
            }
        }else{
            $this->Object = '';
            return $this->Object;
        }
    }
}