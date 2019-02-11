<?php

require 'vendor/autoload.php';

use Billplz\API;
use Billplz\Connect;

$connect = (new Connect('<api-key-here>'))->detectMode();
//$connect->setMode(true); // true: production | false: staging (default)

$billplz = new API($connect);
$response = $billplz->getCollection('_n3gnf0p');

echo '<pre>'.print_r($response, true).'</pre>';
