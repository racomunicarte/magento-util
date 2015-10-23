<?php
/**
 * Download all produc images.
 * */

$MAGENTO_PATH = "/PATH/TO/MAGENTO";
$CSV_FILE = "/PATH/TO/CSV/new-prices.csv";
 
require_once($MAGENTO_PATH."/app/Mage.php");
Mage::app();

$products = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToFilter('status', array('in'=>Mage::getSingleton('catalog/product_status')->getSaleableStatusIds()));

if($products){
	foreach($products as $product){
		$_product = Mage::getModel('catalog/product')->load($magento_product->getId());
		
		$images = array();
		
		// Main image
		$images[] = Mage::getModel('catalog/product_media_config')->getMediaUrl($_product->getImage());
		
		$gallery = Mage::getModel('catalog/product')->load($_product->getId())->getMediaGalleryImages();
		if($gallery){
			foreach($gallery as $image){
				if(array_search($image->getUrl(), $images) === false){
					$images[] = $image->getUrl();
				}
				
			}
		}
		
		var_dump($images);
		die();
	}
}
