<?php

require_once("PATH_TO_MAGENTO/app/Mage.php");
Mage::app();

$order_ids = array('100004544', '100004545', '100004546');

foreach($order_ids as $order_id){
    $order = Mage::getSingleton('sales/order');
    $order->loadByIncrementId($order_id);
    $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
    $order->save();
    echo "CANCEL: ".$order_id."\n";
}

die();
