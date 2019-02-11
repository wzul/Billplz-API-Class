<?php

require 'vendor/autoload.php';

use Billplz\API;
use Billplz\Connect;

$connect = (new Connect('<api-key-here>'))->detectMode();
//$connect->setMode(true); // true: production | false: staging (default)

$parameter = array(
    'name'=>'Insan Bertuah' ,
    'id_no'=>'1311231231',
    'acc_no'=>'999988887756',
    'code'=>'MBBEMYKL',
    'organization'=>'true'
);

$billplz = new API($connect);
$response = $billplz->createBankAccount($parameter);

echo '<pre>'.print_r($response, true).'</pre>';
