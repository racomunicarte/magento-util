<?php
require_once("/PATH/TO/MAGENTO/app/Mage.php");
Mage::app();

$observer = Mage::getModel('YOUR_MODEL_CLASS_NAME');
$observer->YOUR_METHOD_NAME();
