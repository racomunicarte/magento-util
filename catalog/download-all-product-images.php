<?php
/**
 * Download all produc images.
 * */

$MAGENTO_PATH = "/PATH/TO/MAGENTO";
 
require_once($MAGENTO_PATH."/app/Mage.php");
Mage::app();

$products = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToFilter('status', array('in'=>Mage::getSingleton('catalog/product_status')->getSaleableStatusIds()));

if($products){

	$download_dir = "images-".date("Y-m-d_H-i-s");
	exec("mkdir ".$download_dir);
	exec("cd ".$download_dir);

	foreach($products as $product){
		$_product = Mage::getModel('catalog/product')->load($product->getId());

		$product_name = strtolower($_product->getName());
                $product_name = iconv('UTF-8', 'ASCII//TRANSLIT', $product_name);
               	$product_name = str_replace(" ", "-", $product_name);


		$product_image_dir = $download_dir."/".$_product->getSku()."-".$product_name;
		exec("mkdir ".$product_image_dir);
		
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

		if($images){
			$count = 0;
			
			foreach($images as $image){
				$count++;

				$image_name .= $product_name."_".str_pad($count, 2, "0", STR_PAD_LEFT); 
				$image_name .= ".".substr($image, -3);

				exec("wget ".$image." -O".$product_image_dir."/".$image_name);

				$image_name = "";
			}
		}
	}
}
