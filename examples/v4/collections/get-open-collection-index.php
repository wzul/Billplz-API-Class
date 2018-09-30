<?php

require 'vendor/autoload.php';

use Billplz\API;
use Billplz\Connect;

$connect = (new Connect('<api-key-here>'))->detectMode();
//$connect->setMode(true); // true: staging | false: production (default)

$billplz = new API($connect);
$response = $billplz->getOpenCollectionIndex();
//$response = $billplz->getOpenCollectionIndex(array('page'=>'1', 'status'=>'active'));

echo '<pre>'.print_r($response, true).'</pre>';
