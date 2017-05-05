<?php

require_once('public_html/app/Mage.php');
ini_set('display_errors', 1);
Mage::app('admin');


$customerid = 278;
$newpassword = 123123;
$storeid = '1';

$websiteId = Mage::getModel('core/store')->load($storeid)->getWebsiteId();
$customer = Mage::getModel('customer/customer')->load($customerid);
$customer->setPassword($newpassword);
$customer->save();
