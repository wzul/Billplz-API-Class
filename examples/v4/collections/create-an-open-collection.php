<?php

require 'vendor/autoload.php';

use Billplz\API;
use Billplz\Connect;

$connnect = (new Connect('4e49de80-1670-4606-84f8-2f1d33a38670'))->detectMode();
//$connect->setMode(true); // true: staging | false: production (default)

$parameter = array(
    'title' => 'Subscription for 1 week Internet',
    'description' => 'Any description here',
    'amount' => '100',
);

$optional = array(
    'fixed_amount' => 'false',
    'fixed_quantity' => 'true',
    'payment_button' => 'pay',
    'reference_1_label' => 'Reference 1',
    'reference_2_label' => 'Reference 2',
    'email_link' => 'http://google.com',
    'tax' => '0',
    'photo' => '',
    'split_header' => true,
    'split_payments' => array(
        ['split_payments[][email]' => 'wan@billplz.com'],
        ['split_payments[][fixed_cut]' => '100'],
        ['split_payments[][variable_cut]' => ''],
        ['split_payments[][stack_order]' => '0'],
        ['split_payments[][email]' => 'wan+1@billplz.com'],
        ['split_payments[][fixed_cut]' => '100'],
        ['split_payments[][variable_cut]' => ''],
        ['split_payments[][stack_order]' => '1'],
    )
);

$billplz = new API($connnect);
$response = $billplz->createOpenCollection($parameter, $optional);
//$response = $billplz->createOpenCollection($parameter);

echo '<pre>'.print_r($response, true).'</pre>';
