<?php

require 'vendor/autoload.php';

use Billplz\API;
use Billplz\Connect;

$connect = (new Connect('<api-key-here>'))->detectMode();
//$connect->setMode(true); // true: production | false: staging (default)

$parameter = array(
    'collection_id' => 'bbrgyvvo',
    'payment_methods' => array(
        ['payment_methods[][code]' => 'fpx'],
        ['payment_methods[][code]' => 'billplz'],
        ['payment_methods[][code]' => 'boost'],
    )
);

$billplz = new API($connect);
$response = $billplz->updatePaymentMethod($parameter);

echo '<pre>'.print_r($response, true).'</pre>';
