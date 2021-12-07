<?php
namespace Voip\HttpClients;

class HttpClients
{
    protected $curlErrorMessage = '';
    protected $curlErrorCode = 0;
    protected $rawResponse;
    public function __construct()
    {
    }
    public function RequestAuthtication($url = '', $body = '')
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded',),
        ));
        $this->rawResponse = curl_exec($curl);
        curl_close($curl);
        usleep(20000);
        return $this->rawResponse;
    }
    public function RequestGraph($url = '', $Authorization = '', $body = '')
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $Authorization",
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $this->rawResponse = curl_exec($curl);
        curl_close($curl);
        usleep(20000);
        return $this->rawResponse;
    }
}
