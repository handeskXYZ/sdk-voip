<?php
require_once '../vendor/autoload.php';
use Voip\Voip;
use Voip\Graph;
use Voip\Authentication\OAth2;
use Voip\GraphNode\GraphTable as GraphTable;

$voip = new Voip(['api_key' => '603488cce216f7cf95015249d5bb9245',
    'api_secert' => 'f007f6057444d9a7f567163391d2b366',
    ]);
/*
*   Request Token
*/
$voip->getAccessToken();
$accessToken = $voip->oath2->isToken();
/*
*   Request graph call findone
*/
$graph = new Graph();
//$graph->sendRequest($accessToken, 'call/findone', array('callid' => '1623301291.139'));
//$graph->getGraphTable();
//var_dump($graph->GraphTable->getlist());
//var_dump($graph->getMeta);
//var_dump($graph->GraphTable->getlimit());
//var_dump($graph->GraphTable->getOffset());
//var_dump($graph->GraphTable->getSort());
/*
*   Request graph call recording
*/
//$res = $graph->sendRequest($accessToken, 'call/media', array('callid' => '1623301291.139'));
//$graph->getGraphTable();
//var_dump($graph->GraphTable->getlist());
//var_dump($graph->getMeta);
//var_dump($graph->GraphTable->getlimit());
//var_dump($graph->GraphTable->getOffset());
//var_dump($graph->GraphTable->getSort());
/*
*   Request graph call find
*/
//$res = $graph->sendRequest($accessToken, 'call/find', array('date_start' => '2021-9-02','date_end'=>'2021-12-01','type'=>'outbound,inbound,local','offset'=>'0','limit'=>'5'));
//$graph->getGraphTable();
//var_dump($graph->GraphTable->getlist());
//var_dump($graph->getMeta);
//var_dump($graph->GraphTable->getlimit());
//var_dump($graph->GraphTable->getOffset());
//var_dump($graph->GraphTable->getSort());
/*
*   Request graph call mailbox
*/
//$res = $graph->sendRequest($accessToken, 'call/mailbox', array('date_start' => '2021-9-1','date_end'=>'2021-10-01'));
//$graph->getGraphTable();
//var_dump($graph->GraphTable->getlist());
//var_dump($graph->getMeta);
//var_dump($graph->GraphTable->getlimit());
//var_dump($graph->GraphTable->getOffset());
//var_dump($graph->GraphTable->getSort());
// $res = $graph->sendRequest($accessToken, 'pbx/dialup', array('extension' => '997','phone'=>'0369975766'));
// $graph->getGraphData();
// var_dump($graph->GraphData->getData());

// $res = $graph->sendRequest($accessToken, 'pbx/hangup', array('extension' => '999'));
// $graph->getGraphData();
// var_dump($graph->GraphData->getData());

// $res = $graph->sendRequest($accessToken, 'pbx/calltransfer', array('extension_transfer' => 997, 'extension' => 999 ));
// $graph->getGraphData();
// var_dump($graph->GraphData->getData());

// $res = $graph->sendRequest($accessToken, 'pbx/peers', array('extension' => 994));
// $graph->getGraphData();
// var_dump($graph->GraphData->getData());

// $res = $graph->sendRequest($accessToken, 'pbx/channels', array('extension' => '994,999'));
// $graph->getGraphData();
// var_dump($graph->GraphData->getData());

// $res = $graph->sendRequest($accessToken, 'pbx/pickup', array('extension_in' => 994, 'extension_out' => 997));
// $graph->getGraphData();
// var_dump($graph->GraphData->getData());

// $res = $graph->sendRequest($accessToken, 'pbx/spy', array('extension' => 992, 'sip_spy' => 996));
// $graph->getGraphData();
// var_dump($graph->GraphData->getData());






