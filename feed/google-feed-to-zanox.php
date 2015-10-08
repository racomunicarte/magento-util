<?php
Header('Content-type: text/xml');
$xml = file_get_contents('URL/TO/MERCHANT.xml', true);
// no troubles with ":"
$xml = str_replace("g:", "g-", $xml);
$json_google_feed = json_decode(json_encode((array) simplexml_load_string($xml)),1);
echo "<produtos>";
foreach($json_google_feed["channel"]["item"] as $prod){
	echo "<produto>";
        echo "<PROD_NUMBER>".trim($prod["g-mpn"])."</PROD_NUMBER>";
        echo "<PROD_NAME><![CDATA[".trim($prod["title"])."]]></PROD_NAME>";
        echo "<PROD_URL><![CDATA[".trim($prod["link"])."?utm_source=zanox&utm_medium=xml&utm_campaign=geral]]></PROD_URL>";
        echo "<FOTO_URL><![CDATA[".trim($prod["g-image_link"])."]]></FOTO_URL>";
        echo "<IMG_MEDIUM><![CDATA[".trim($prod["g-image_link"])."]]></IMG_MEDIUM>";
        echo "<IMG_LARGE><![CDATA[".trim($prod["g-image_link"])."]]></IMG_LARGE>";
        echo "<CATEGORY><![CDATA[Móveis]]></CATEGORY>";
        echo "<PROD_DESCRIPTION><![CDATA[".trim($prod["description"])."]]></PROD_DESCRIPTION>";
        
        $price = trim($prod["g-sale_price"]);
	$price = str_replace(" BRL", "", $price);
        
        echo "<PROD_PRICE_OLD>".$price."</PROD_PRICE_OLD>";
        
        $price = trim($prod["g-price"]);
	$price = str_replace(" BRL", "", $price);
        
	echo "<PROD_PRICE>".$price."</PROD_PRICE>";
        echo "<MANUFACTURER><![CDATA[BRAND]]></MANUFACTURER> ";
        echo "<CURRENCY_SIMBOL><![CDATA[BRL]]></CURRENCY_SIMBOL>";
        echo "<PROD_DESCRIPTION_LONG><![CDATA[".trim($prod["description"])."]]></PROD_DESCRIPTION_LONG>";
	echo "</produto>";
}
echo "</produtos>";
?>

