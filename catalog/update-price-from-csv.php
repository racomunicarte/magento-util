<?php
/**
 * Update Magento product prices from CSV file.
 * 
 * Run (command line): php update-price-from-csv.php
 * */


$MAGENTO_PATH = "/PATH/TO/MAGENTO";
$CSV_FILE = "/PATH/TO/CSV/new-prices.csv";
 
require_once($MAGENTO_PATH."/app/Mage.php");
Mage::app();

$prices_from_csv = csvToArray($CSV_FILE);
 
foreach($prices_from_csv as $price){
	$product = Mage::getModel('catalog/product')->loadByAttribute('sku', $price["sku"]);
	
	if($price['price']){
		$product->setPrice($price['price']);
	}
	
	if($price['special_price']){
		$product->setSpecialPrice($price['special_price']);
	}

	$product->save();
	
	echo $price["sku"]."\t".$product->getName()."\t".$price['price']."\t".$price['special_price'];
	echo "\n";
}
 
die('The end.');

function csvToArray($file){
	if (($handle = fopen($file, "r")) !== FALSE) {
		$headers = array();
		$return = array();
		$count = 0;
		while (($data = fgetcsv($handle, filesize($file), "\t")) !== FALSE) {
			if($count == 0){
				$headers = $data;
			} else {
				$count_data = count($data);
				$p = array();
				for ($c=0; $c < $count_data; $c++) {
					$p[$headers[$c]] = $data[$c];
				}	
				$return[$count] = $p;
			}
			$count++;
		}
		fclose($handle);
	}
	return $return;
}


function arrayToCsv($file, $array){
	if (($handle = fopen($file, "w")) !== FALSE) {
		$csv = "";
		$headers = implode("\t", array_keys($array[0]));
		$csv .= $headers;
		$csv .= "\n";
		
		foreach($array as $line){
			$csv .= implode("\t", trim($line));
			$csv .= "\n";
		}
		fwrite($handle, $csv);
		fclose($handle);
	}
}
