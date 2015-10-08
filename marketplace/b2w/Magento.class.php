<?php

require_once(PATH_TO_MAGENTO."/app/Mage.php");

class Magento{
	
	public $magentoApp;
	
	public function Magento(){
		$this->magentoApp = Mage::app();
	}
	
	public function getAllSableProducts(){
		$products = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToFilter('status', array('in'=>Mage::getSingleton('catalog/product_status')->getSaleableStatusIds()));
		return $products;
	}
}
