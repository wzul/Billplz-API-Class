<?php

/*
 * This file shows how to Create A Bill using BillplzCURL class
 * The commented line is Optional parameter
 * 
 */
require 'billplz.php';

$a = new BillplzCURL;
$a->setName("Wan Zulkarnain");
$a->setAmount('10.60');
$a->setEmail('wan@wanzul-hosting.com');
$a->setDescription("Test");
$a->setPassbackURL('http://callback-url.com', 'http://redirect-url.com');
//$a->setCollection('ypppifx4m');
//$a->setPassbackURL('http://callback-url.com');
//$a->setReference_1('Exam');
//$a->setReference_1_Label('A');
//$a->setMobile('0145356443');
//$a->setDeliver('0'); //No Notification
//$a->setDeliver('1'); //Email Notification
//$a->setDeliver('2'); //SMS Notification
//$a->setDeliver('3'); //Email & SMS Notification
//$a->create_bill('4e49de80-1670-4606-84f8-2f1d33a38670', true);
$a->create_bill('4e49de80-1670-4606-84f8-2f1d33a38670');
echo $a->getURL();
