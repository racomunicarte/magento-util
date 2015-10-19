<?php 
Header('Content-type: text/xml');

$MAGENTO_PATH = "/PATH/TO/MAGENTO";

require_once($MAGENTO_PATH."/app/Mage.php");
Mage::app();

$products = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToFilter('status', array('in'=>Mage::getSingleton('catalog/product_status')->getSaleableStatusIds()));

$xml = "";
if($products){
	
	//iPagare Checkout
	$installments = Mage::getStoreConfig('ipgintegracaodiretapagamento/cielo');
	$interest_free = $installments["parcelas_sem_juros"];
	
	/*$xml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";*/
	$xml .= "<rss xmlns:g=\"http://base.google.com/ns/1.0\" version=\"2.0\">";
		$xml .= "<channel>";
			$xml .= "<title>Store name</title>";
			$xml .= "<link>Store URL</link>";
			$xml .= "<description>Store Descripion</description>";

			foreach($products as $product){				
				$loaded_product = Mage::getModel('catalog/product')->load($product->getId());
				
				$xml .= "<item>";
					$xml .= "<g:id>".$loaded_product->getId()."</g:id>";
					$xml .= "<title>".$loaded_product->getName()."</title>";
					$xml .= "<description>".htmlspecialchars($loaded_product->getDescription())."</description>";
					$xml .= "<link>".$loaded_product->getProductUrl()."</link>";
					$xml .= "<g:image_link>".Mage::getModel('catalog/product_media_config')->getMediaUrl($loaded_product->getImage())."</g:image_link>";
					$xml .= "<g:price>".number_format($loaded_product->getPrice(), 2, ".", "")." BRL</g:price>";
					$xml .= "<g:sale_price>".number_format($loaded_product->getSpecialPrice(), 2, ".", "")." BRL</g:sale_price>";					
					$xml .= "<g:installment><g:months>".$interest_free."</g:months><g:amount>".number_format($loaded_product->getSpecialPrice()/$interest_free, 2, ".", "")." BRL</g:amount></g:installment>";

					$stock = "out of stock";
					if((float)Mage::getModel('cataloginventory/stock_item')->loadByProduct($loaded_product)->getQty() > 0){
						$stock = "in stock";
					}
					$xml .= "<g:availability>".$stock."</g:availability>";
					
					$xml .= "<g:shipping_weight>".$loaded_product->getWeight()." kg</g:shipping_weight>";
					
					$exp = date('Y-m-d',strtotime(date() . "+30 days"));
					$xml .= "<g:expiration_date>".$exp."</g:expiration_date>";
					
					$xml .= "<g:brand>Brand name</g:brand>";
					$xml .= "<g:condition>new</g:condition>";

					
					$cats = $product->getCategoryIds();
					$category = "Móveis > Móveis para escritório > Cadeiras de escritório";
					if(array_search(48, $cats) !== false){
						$category = "Móveis > Cadeiras > Cadeiras de cozinha e sala de jantar";
					} elseif(array_search(71, $cats) !== false){
						$category = "Móveis > Cadeiras > Cadeiras de cozinha e sala de jantar";
					}

					$xml .= "<g:product_type>".$category."</g:product_type>";
					$xml .= "<g:google_product_category>".$category."</g:google_product_category>";
					
					$xml .= "<g:mpn>".$loaded_product->getSku()."</g:mpn>";
				$xml .= "</item>";
			}
		$xml .= "</channel>";
	$xml .= "</rss>";
}
echo $xml;
