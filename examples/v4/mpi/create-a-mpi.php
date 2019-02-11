<?php

require 'vendor/autoload.php';

use Billplz\API;
use Billplz\Connect;

$connect = (new Connect('<api-key-here>'))->detectMode();
//$connect->setMode(true); // true: production | false: staging (default)

$parameter = array(
    'mass_payment_instruction_collection_id' => 'go0voexz',
    'bank_code'=> 'MBBEMYKL',
    'bank_account_number' => '300',
    'identity_number'=>'JJJ',
    'name' => 'SS',
    'description' => 'ntah berantah',
    'total' => '200'
);
$optional = array(
    'email' => 'youremail@gmail.com',
    'notification'=>'false',
    'recipient_notification' => 'false'
);

$billplz = new API($connect);
$response = $billplz->createMPI($parameter);
//$response = $billplz->createMPI($parameter,$optional);

echo '<pre>'.print_r($response, true).'</pre>';
