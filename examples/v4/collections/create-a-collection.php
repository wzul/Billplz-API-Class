<?php

require 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 'On');

use Billplz\API;
use Billplz\Connect;

$connect = (new Connect('<api-key-here>'))->detectMode();
//$connect->setMode(true); // true: production | false: staging (default)

$billplz = new API($connect);

$collectionName = 'My First Collection';

$optional  = array(
    /* Does not supported to post logo */
    'split_header' => true,
    'split_payments' => array(
            ['split_payments[][email]' => 'youremail@gmail.com'],
            ['split_payments[][fixed_cut]' => '100'],
            ['split_payments[][variable_cut]' => ''],
            ['split_payments[][stack_order]' => '0'],
            ['split_payments[][email]' => 'wan+1@billplz.com'],
            ['split_payments[][fixed_cut]' => '100'],
            ['split_payments[][variable_cut]' => ''],
            ['split_payments[][stack_order]' => '1'],
    ),
    
);
$response = $billplz->createCollection($collectionName);
//$response = $billplz->createCollection($collectionName, $optional);

echo '<pre>'.print_r($response, true).'</pre>';
