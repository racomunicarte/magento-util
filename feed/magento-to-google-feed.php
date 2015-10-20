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
	
	$xml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xml .= "<rss xmlns:g=\"http://base.google.com/ns/1.0\" version=\"2.0\">\n";
		$xml .= "<channel>\n";
			$xml .= "\t<title>STORE NAME</title>\n";
			$xml .= "\t<link>STORE URL</link>\n";
			$xml .= "\t<description>STORE DESCRIPTION</description>\n";

			foreach($products as $product){				
				$loaded_product = Mage::getModel('catalog/product')->load($product->getId());
				
				$xml .= "\t<item>\n";
					$xml .= "\t\t<g:id>".$loaded_product->getId()."</g:id>\n";
					$xml .= "\t\t<title>".$loaded_product->getName()."</title>\n";
					$xml .= "\t\t<description>".htmlspecialchars($loaded_product->getDescription())."</description>\n";
					$xml .= "\t\t<link>".$loaded_product->getProductUrl()."</link>\n";
					$xml .= "\t\t<g:image_link>".Mage::getModel('catalog/product_media_config')->getMediaUrl($loaded_product->getImage())."</g:image_link>\n";
					$xml .= "\t\t<g:price>".number_format($loaded_product->getPrice(), 2, ".", "")." BRL</g:price>\n";
					$xml .= "\t\t<g:sale_price>".number_format($loaded_product->getSpecialPrice(), 2, ".", "")." BRL</g:sale_price>\n";
					$xml .= "\t\t<g:installment><g:months>".$interest_free."</g:months><g:amount>".number_format($loaded_product->getSpecialPrice()/$interest_free, 2, ".", "")." BRL</g:amount></g:installment>\n";

					$stock = "out of stock";
					if((float)Mage::getModel('cataloginventory/stock_item')->loadByProduct($loaded_product)->getQty() > 0){
						$stock = "in stock";
					}
					$xml .= "\t\t<g:availability>".$stock."</g:availability>\n";
					
					$xml .= "\t\t<g:shipping_weight>".$loaded_product->getWeight()." kg</g:shipping_weight>\n";
					
					$exp = date('Y-m-d',strtotime(date() . "+30 days"));
					$xml .= "\t\t<g:expiration_date>".$exp."</g:expiration_date>\n";
					
					$xml .= "\t\t<g:brand>STORE BRAND</g:brand>\n";
					$xml .= "\t\t<g:condition>new</g:condition>\n";

					
					$cats = $product->getCategoryIds();
					$category = "Móveis > Móveis para escritório > Cadeiras de escritório";
					if(array_search(48, $cats) !== false){
						$category = "Móveis > Cadeiras > Cadeiras de cozinha e sala de jantar";
					} elseif(array_search(71, $cats) !== false){
						$category = "Móveis > Mesas > Mesas de cozinha e sala de jantar";
					} elseif(array_search(79, $cats) !== false){
                                                $category = "Móveis > Conjuntos de móveis > Conjuntos de móveis para cozinha e sala de jantar";
                                        } elseif(array_search(57, $cats) !== false){
                                                $category = "Móveis > Móveis para escritório > Conjuntos de móveis para escritório";
                                        }


					$xml .= "\t\t<g:product_type>".$category."</g:product_type>\n";
					$xml .= "\t\t<g:google_product_category>".$category."</g:google_product_category>\n";
					
					$xml .= "\t\t<g:mpn>".$loaded_product->getSku()."</g:mpn>\n";
				$xml .= "\t</item>\n";
			}
		$xml .= "</channel>\n";
	$xml .= "</rss>\n";
}
echo $xml;
