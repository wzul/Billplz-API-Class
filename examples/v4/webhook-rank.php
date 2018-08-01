<?php

require 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 'On');

use Billplz\Connect;

$connect = (new Connect('3f12b915-8763-48d1-99bb-7ff0d771a06c'))->detectMode();
//$connect->setMode(true); // true: staging | false: production (default)

//$response = $connect->toArray($connect->getWebhookRank());
//$webhook_rank = $response[1]['rank'];
$webhook_rank = $connect->webhook_rank;

echo '<pre>'.print_r($webhook_rank, true).'</pre>';
